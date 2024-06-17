<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import { ElCard, ElCollapse, ElNotification, ElCollapseItem } from 'element-plus';
import { Icon } from '@iconify/vue';
import { read, utils } from 'xlsx'
import DashLayout from '@/Layouts/DashLayout.vue';
const page = usePage()

const formTambah = ref(false)
const formImpor = ref(false)
const selectedMapel = ref('')
const tambah = (mapel) => {
    selectedMapel.value = mapel
    formTambah.value = true
}

const closeForm = () => {
    selectedMapel.value = ''
    newTp.value = {}
    formTambah.value = false
}

const newTp = ref({})

const role = computed(() => page.props.auth.roles[0])

const onFileElemenPicked = async(e) => {
    const file = e.target.files[0]
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    const ws = wb.Sheets[wb.SheetNames[0]];
    let datas = utils.sheet_to_json(ws)
    await router.post(route('dashboard.pembelajaran.elemen.impor'), {elemens: datas}, {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: page.props.flash.message, type:'success'})
            router.reload({only: ['mapels']})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type:'error'})
                }, 500)
            })
        }
    })
}
const onFileTpPicked = async(e) => {
    // console.log(mapel)
    const file = e.target.files[0]
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    const ws = wb.Sheets[wb.SheetNames[0]];
    let datas = utils.sheet_to_json(ws)
    await router.post(route('dashboard.pembelajaran.tp.impor'), {tps: datas}, {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: page.props.flash.message, type:'success'})
            router.reload({only: ['mapels']})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type:'error'})
                }, 500)
            })
        }
    })
}

const editTp = (item, mapel) => {
    selectedMapel.value = mapel
    newTp.value = item
    formTambah.value = true
}

const simpanTp = async() => {
    // console.log(newTp.value)
    newTp.value.mapel_id = selectedMapel.value.kode
    router.post(route('dashboard.pembelajaran.tp.store'), newTp.value, {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: page.props.flash.message, type:'success'})
            router.reload({only: ['mapels']})
            newTp.value = {}
            selectedMapel.value = {}
            formTambah.value = false
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type:'error'})
                }, 500)
            })
        }
    })
}

const hapusTp = async(id) => {
    router.delete(route('dashboard.pembelajaran.tp.destroy', {id: id}), {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: page.props.flash.message, type:'success'})
            router.reload({only: ['mapels']})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type:'error'})
                }, 500)
            })
        }
    })
}

</script>

<template>
<Head title="Pembelajaran" />
<DashLayout>
    <template #header>
        Pembelajaran
    </template>
    <el-card shadow="never">
        <template #header>
            <span class="title font-bold text-lg">Konten Pembelajaran</span>
        </template>
        <div class="card-body ">
            <el-row>
                <el-col>
                    <div class="card border">
                        <div class="card-header bg-slate-100 p-2 flex items-center justify-between">
                            <h3 class="font-bold">Mata Pelajaran</h3>
                            <span>
                                <el-button size="small" type="primary" @click="$refs.filElemen.click()" :disabled="role !== 'admin'">Impor Elemen</el-button>
                                <el-button type="success" size="small" @click="$refs.fileTp.click()" :disabled="role !== 'admin'">Impor TP</el-button>
                                <input type="file" ref="filElemen" accept=".xls,.xlsx,.ods" class="hidden" @change="onFileElemenPicked" />
                                <input type="file" ref="fileTp" accept=".xls,.xlsx,.ods" class="hidden" @change="onFileTpPicked" />
                            </span>
                        </div>
                        <div class="card-body p-2">
                        <template v-for="(mapel, m) in page.props.mapels" :key="m">
                            <el-collapse>
                                <el-collapse-item>
                                    <template #title>
                                        <h3>{{ mapel.label }}</h3>
                                    </template>
                                    <div class="collapse-body p-2 bg-sky-100">
                                        <div class="collapse-body--tile flex justify-between mb-2">
                                                <h4 class="font-bold text-slate-600">Data Tujuan Pembelajaran</h4>
                                                <el-button-group>
                                                    <el-button type="primary" size="small" @click="tambah(mapel)" :disabled="role !== 'admin'">Tambah TP</el-button>
                                                    
                                                </el-button-group>
                                        </div>
                                        <el-table :data="mapel.tps" class="shadow" height="400">
                                            <el-table-column label="#" type="index" width="50" />
                                            <el-table-column label="Fase" prop="fase" width="55" />
                                            <el-table-column label="Kelas" prop="tingkat" width="60" />
                                            <el-table-column label="Sem" prop="semester" width="60" />
                                            <el-table-column label="Agama" prop="agama" width="85" v-if="mapel.kode == 'pabp'" />
                                            <el-table-column label="Kode" width="125">
                                                <template #default="scope">
                                                    <el-button text type="primary" @click="editTp(scope.row, mapel)">{{ scope.row.kode }}</el-button>
                                                </template>
                                            </el-table-column>
                                            <el-table-column label="Teks" prop="teks" />
                                            <el-table-column label="Opsi" width="100">
                                                <template #default="scope">
                                                    <span>
                                                        <el-button circle type="danger" size="small" @click="hapusTp(scope.row.id )" :disabled="role !== 'admin'">
                                                            <Icon icon="mdi-close" />
                                                        </el-button>
                                                    </span>
                                                </template>
                                            </el-table-column>
            
                                        </el-table>
                                    </div>
                                </el-collapse-item>
                            </el-collapse>
                        </template>
                        </div>
                    </div>
                </el-col>
            </el-row>
        </div>
    </el-card>

    <el-dialog class="form-tambah" v-model="formTambah" :show-close="false" @close="closeForm">
        <template #header="{close}">
            <span class="flex justify-between">
                <h3>Tambah <span class="font-black">TP</span> <small>{{ selectedMapel.label }}</small></h3>
                <el-button type="danger" size="small" circle @click="close">
                    <Icon icon="mdi-close"  />
                </el-button>
            </span>
        </template>
        <div class="dialog-body">
            <el-form v-model="newTp" label-position="top">
                <el-row :gutter="10">
                    <el-col :span="4">
                        <el-form-item label="Kelas">
                            <el-select v-model="newTp.tingkat" placeholder="Pilih Tingkat/Kelas">
                                <el-option v-for="t of 6" :key="t" :value="t" :label="`Kelas ${t}`" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="4">
                        <el-form-item label="Kode TP">
                            <el-input v-model="newTp.kode" placeholder="Kode TP" />
                        </el-form-item>
                    </el-col>
                    <el-col :span="10">
                        <el-form-item label="Elemen">
                            <el-select v-model="newTp.elemen" placeholder="Pilih Elemen">
                                <el-option v-for="(elemen, e) in page.props.elemens.filter(el => el.mapel_id == selectedMapel.kode)" :key="e" :value="elemen.nama" :label="`${selectedMapel.kode == 'pabp' ? elemen.agama +'|' : ''} ${elemen.nama}`" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="6">
                        <el-form-item label="Semester">
                            <el-select v-model="newTp.semester" placeholder="Pilih Semester">
                                <el-option v-for="s of 2" :key="`s${s}`" :value="s" :label="`Semester ${s == 1 ? 'Ganjil' : 'Genap'}`" />
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="10" v-if="selectedMapel.kode == 'pabp'">
                    <el-col :span="8">
                        <el-form-item>
                            <el-select v-model="newTp.agama" placeholder="Pilih Agama">
                                <el-option v-for="agama in ['Islam','Kristen','Katolik','Hindu','Budha','Konghuchu']" :key="agama" :value="agama"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="10">
                    <el-col>
                        <el-input type="textarea" placeholder="Teks Tujuan Pembelajaran" v-model="newTp.teks" rows="4"></el-input>
                    </el-col>
                </el-row>
                <el-row align="middle" justify="center" class="mt-4">
                    <el-button type="primary" size="small" @click="simpanTp">Simpan</el-button>
                </el-row>
            </el-form>
        </div>
    </el-dialog>
</DashLayout>
</template>