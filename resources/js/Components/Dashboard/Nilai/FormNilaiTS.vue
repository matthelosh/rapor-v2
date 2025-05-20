<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { ElDialog, ElNotification } from 'element-plus';
import { Icon } from '@iconify/vue'
import axios from 'axios'
import { read, utils, write, writeFile } from 'xlsx'
const page = usePage()

const props = defineProps({rombel: Object, open: Boolean, sekolah: Object, mapel: Object})
const emit = defineEmits(['close'])
const role = page.props.auth.roles[0]

const tipe = ref('PTS')
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
            semester: route().params.semester ?? page.props.periode.semester.kode,
            tapel: page.props.periode.tapel.kode,
            tipe: 'ts'
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

// const getTps = async() => {
//     await axios.post(route('dashboard.pembelajaran.tp.index', {
//         _query: {
//             tingkat: props.rombel.tingkat,
//             mapelId: props.mapel?.kode,
//             agama: page.props.auth.roles.includes('guru_agama') ? page.props.auth.user.userable.agama : null,
//             semester: page.props.periode.semester.kode
//         }
//     })).then(res => {
//         tps.value = res.data.tps
//         loading.value = false
//         props.rombel.siswas.forEach((siswa, s) => {
//             let ns = {}
//             tps.value.map((tp, t) => ns[tp.kode] = 0)
//             siswas.value.push({id: siswa.id, nisn: siswa.nisn, nilais: ns})
//         })
//     })
// }
const params = route().params
const getNilai = async() => {
    await axios.post(route('dashboard.nilai.index', {
        _query: {
            rombelId: props.rombel.kode,
            tingkat: props.rombel.tingkat,
            mapelId: props.mapel?.kode,
            agama: page.props.auth.roles.includes('guru_agama') ? page.props.auth.user.userable.agama : null,
            semester: params.semester ?? page.props.periode.semester.kode,
            tapel: page.props.periode.tapel.kode,
            tipe: 'ts'
        }
    })).then( res => {
        if (res.data.length > 0) {
            siswas.value.forEach(siswa => {
                res.data.filter(nilai => nilai.siswa_id == siswa.nisn).forEach(n => {
                    // console.log(n)
                    siswa.nilai = n.skor
                })
            })
        }
    })
}

const onFileNilaiPicked = async(e) => {
    const file = e.target.files[0]
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    if (wb.SheetNames[0] !== tipe.value) {
        ElNotification({title: 'Error! Mapel Tidak Sesuai', message: 'Mapel Nilai yang Anda impor tidak sesuai', type: 'error'})
        return false
    }
    const ws = wb.Sheets[wb.SheetNames[0]];

    const datas = utils.sheet_to_json(ws)
    siswas.value.forEach(siswa => {
        datas.forEach(data => {
            if (siswa.nisn == data.nisn) {
                siswa.nilai = data.nilai
            }
        })
    })

}

const unduhFormat = async() => {
    let data = []
    siswas.value.forEach(siswa => {
        data.push({nisn: siswa.nisn, nama: siswa.nama, jk: siswa.jk, agama: siswa.agama, nilai: siswa.nilai??0})
    })
    const ws = utils.json_to_sheet(data)
    const wb = utils.book_new()
    utils.book_append_sheet(wb, ws, "PTS")
    writeFile(wb, "Format Impor Nilai PTS "+ props.mapel.label +"  "+props.rombel.label+" Semester "+ page.props.periode.semester.label +" "+ page.props.periode.tapel.label+".xlsx")

}

onBeforeMount(async() => {
    // loading.value = true
    // await getTps()
    props.rombel.siswas.forEach(siswa => {
        siswa.nilai = 0
        siswas.value.push(siswa)
    })
    await getNilai()

})
</script>

<template>
<el-dialog v-model="props.open" fullscreen @close="emit('close')" :show-close="false">
    <template #header="{ close, titleId, titleClass }">
        <span class="uppercase">
            <span class="flex items-start justify-between">
                <div>
                    <p>Nilai Tengah Semester {{page.props.periode.semester.label}} {{ page.props.periode.tapel.label }}</p>
                    <!-- {{ props.mapel }} -->
                    <p class="text-sky-800 font-black">{{ props.mapel.label ? props.mapel.label : (!props.mapel.kode.includes('pabp') ? props.mapel.kode.toUpperCase() : `Pendidikan Agama ${page.props.auth.user.userable.agama}`) }} </p>
                    <p>
                        {{ props.rombel.label }}
                        <span>{{ role !== 'guru_kelas' ? props.sekolah.nama : props.rombel.sekolah.nama }}</span>
                    </p>
                </div>
                <div class="items flex items-start gap-6">
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
    <div class="dialog-body px-14">
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
                <el-table :data="props.rombel.siswas" height="86.5vh" size="small">
                    <el-table-column type="index" label="#" width="50" fixed></el-table-column>
                    <el-table-column label="NISN" prop="nisn" width="120" fixed />
                    <el-table-column label="Nama" prop="nama" fixed :sortable="true" />
                    <el-table-column label="Jenis KelaminK" width="100">
                        <template #default="scope">
                            {{ scope.row.jk }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Agama" width="100">
                        <template #default="scope">
                            {{ scope.row.agama }}
                        </template>
                    </el-table-column>
                    <el-table-column  width="90">
                        <template #header>
                            <span class="cursor-pointer">
                                N. PTS
                            </span>
                        </template>
                        <template #default="scope">
                            <el-input v-model="siswas[scope.$index].nilai" type="number" min="0" max="100" size="small" />
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </el-skeleton>
    </div>
</el-dialog>

</template>
