<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { ElDialog, ElNotification } from 'element-plus';
import { Icon } from '@iconify/vue'
import axios from 'axios'
import { read, utils, writeFile } from 'xlsx'
const page = usePage()

const props = defineProps({rombel: Object, open: Boolean, sekolah: Object, mapel: Object})
const emit = defineEmits(['close'])
const role = page.props.auth.roles[0]

const tps = ref(['TP1','TP2', 'TP3'])

const tipe = ref('UH')
const siswas = ref([])
const loading = ref(false)
const simpan = async() => {
    // console.log(siswas.value)
    router.post(route('dashboard.nilai.store', {
        _query: {
            rombelId: props.rombel.kode,
            tingkat: props.rombel.tingkat,
            mapelId: props.mapel?.kode,
            agama: page.props.auth.roles.includes('guru_agama') ? page.props.auth.user.userable.agama : null,
            semester: page.props.periode.semester.kode,
            tapel: page.props.periode.tapel.kode,
            tipe: 'uh'
        }
    }), {siswas: siswas.value}, {
        onSuccess: (page) => {
            router.reload({only: ['nilais']})
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
        }, 
        onError: errs => {
            // console.log(errs)
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500)
            })
        }
    })
}

const getTps = async() => {
    await axios.post(route('dashboard.pembelajaran.tp.index', {
        _query: {
            tingkat: props.rombel.tingkat,
            mapelId: props.mapel?.kode,
            agama: page.props.auth.roles.includes('guru_agama') ? page.props.auth.user.userable.agama : null,
            semester: page.props.periode.semester.kode
        }
    })).then(res => {
        tps.value = res.data.tps
        loading.value = false
        props.rombel.siswas.forEach((siswa, s) => {
            let ns = {}
            tps.value.map((tp, t) => ns[tp.kode] = 0)
            siswas.value.push({id: siswa.id, nisn: siswa.nisn, nama:siswa.nama, nilais: ns, jk: siswa.jk, agama: siswa.agama})
        })
    })
}

const getNilai = async() => {
    await axios.post(route('dashboard.nilai.index', {
        _query: {
            rombelId: props.rombel.kode,
            tingkat: props.rombel.tingkat,
            mapelId: props.mapel?.kode,
            agama: page.props.auth.roles.includes('guru_agama') ? page.props.auth.user.userable.agama : null,
            semester: page.props.periode.semester.kode,
            tapel: page.props.periode.tapel.kode,
            tipe: 'uh'
        }
    })).then( res => {
        if (res.data.length > 0) {
            siswas.value.forEach(siswa => {
                res.data.filter(nilai => nilai.siswa_id == siswa.nisn).forEach(n => {
                    // console.log(n)
                    siswa.nilais[n.tp_id] = n.skor
                })
            })
        }
    })
}

const onFileNilaiPicked = async(e) => {
    const file = e.target.files[0]
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    const ws = wb.Sheets[wb.SheetNames[0]];
    if (wb.SheetNames[0] !== props.mapel.kode) {
        ElNotification({title: 'Error! Mapel Tidak Sesuai', message: 'Mapel Nilai yang Anda impor tidak sesuai', type: 'error'})
        return false
    }
    const datas = utils.sheet_to_json(ws)
    // console.log(datas)
    siswas.value.forEach(siswa => {
        datas.forEach(data => {
            if (siswa.nisn == data.nisn) {
                Object.keys(data).forEach(k => {
                    if(!['no','nisn','nama','jk','agama'].includes(k)) {
                        siswa.nilais[k] = data[k]
                        // console.log(k)
                    }
                })
            }
        })
    })

}

const unduhFormat = async() => {
    let data = []
    await siswas.value.forEach(siswa => {
        let item = {nisn: siswa.nisn, nama: siswa.nama, jk: siswa.jk, agama: siswa.agama}
        Object.keys(siswa.nilais).forEach((k) => {
            item[k] = siswa.nilais[k]
        })
        data.push(item)
        // console.log(siswa.nilais)
    })
    const ws = utils.json_to_sheet(data)
    const wb = utils.book_new()
    utils.book_append_sheet(wb, ws, props.mapel.kode)
    writeFile(wb, "Impor Nilai Harian "+props.mapel.label+" Kelas "+props.rombel.label+" Semester "+ page.props.periode.semester.label +" "+ page.props.periode.tapel.label+".xlsx")

}

onBeforeMount(async() => {
    loading.value = true
    await getTps()
    await getNilai()

})
</script>

<template>
<el-dialog v-model="props.open" fullscreen @close="emit('close')" :show-close="false">
    <template #header="{ close, titleId, titleClass }">
        <span class="uppercase">
            <span class="flex items-center justify-between">
                <div>
                    <small>Nilai Harian</small> <span class="text-sky-800 font-bold">{{ props.mapel.label ? props.mapel.label : (!props.mapel.kode.includes('pabp') ? props.mapel.kode.toUpperCase() : `Pendidikan Agama ${page.props.auth.user.userable.agama}`) }} </span> 
                    <!-- <span v-if="role == 'guru_kelas'">{{ props.mapel.label }} </span> -->
                    <small>
                        &nbsp;
                        {{ props.rombel.label }} 
                        <span>{{ role !== 'guru_kelas' ? props.sekolah.nama : page.props.sekolahs[0].nama }}</span>
                    </small>
                </div>
                <div class="items flex items-center gap-6">
                    <input type="file" ref="fileNilai" accept=".xls,.xlsx,.ods" @change="onFileNilaiPicked" class="hidden" />
                    <el-button-group>
                        <el-button type="success" size="small" @click="unduhFormat">Unduh Format</el-button>
                        <el-button type="warning" size="small" @click="$refs.fileNilai.click()">Impor</el-button>
                        <el-button type="primary" size="small" @click="simpan">Simpan</el-button>
                    </el-button-group>
                    <el-button type="danger" @click="close" size="small">
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </span>
        </span>
    </template>
    <div class="dialog-body">
        <el-skeleton :loading="loading" animated>
            <template #template>
                <div v-for="d of 16" :key="d" style="width: 100%; display: flex; margin-bottom: 20px; align-items: middle;">
                    <el-skeleton-item variant="text" style="width:50px;   height: 30px;  margin: 0 5px;" />
                    <el-skeleton-item variant="text" style="width: 100px; height: 30px;  margin: 0 5px;" />
                    <el-skeleton-item variant="text" style="width:50%;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:50px;   height: 30px;  margin: 0 5px;" />
                    <el-skeleton-item variant="text" style="width:50px;   height: 30px;  margin: 0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px 0 20px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                    <el-skeleton-item variant="text" style="width:40px; height: 30px;  height: 30px; margin:0 5px;" />
                </div>
            </template>
            <template #default>
                <el-table :data="props.rombel.siswas" height="90vh" size="small">
                    <el-table-column type="index" label="#" width="50" fixed></el-table-column>
                    <el-table-column label="NISN" prop="nisn" width="120" fixed />
                    <el-table-column label="Nama" prop="nama" fixed  width="230"/>
                    <el-table-column label="JK" width="60">
                        <template #default="scope">
                            {{ scope.row.jk.substring(0,1) }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Agm" width="55">
                        <template #default="scope">
                            {{ scope.row.agama.substring(0,2) }}
                        </template>
                    </el-table-column>
                    <template v-for="(tp,t) in tps" :key="t">
                        <el-table-column  width="90">
                            <template #header>
                                <span class="cursor-pointer">
                                    <el-popover placement="bottom-end">
                                        <template #reference>
                                            <span class="text-sky-700">{{ tp.kode }}</span>
                                        </template>
                                        <div>
                                            <h3 class="font-bold text-sky-600"><small>{{ tp.elemen }}</small></h3>
                                            <small>
                                                {{ tp.teks }}
                                            </small>
                                        </div>
                                    </el-popover>
                                </span>
                            </template>
                            <template #default="scope">
                                <el-input v-model="siswas[scope.$index].nilais[tp.kode]" type="number" :tabindex="t" min="0" max="100" size="small" />
                            </template>
                        </el-table-column>
                    </template>
                </el-table>
            </template>
        </el-skeleton>
    </div>
</el-dialog>

</template>