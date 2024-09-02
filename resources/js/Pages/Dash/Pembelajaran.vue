<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import { ElCard, ElCollapse, ElNotification, ElCollapseItem } from 'element-plus';
import { Icon } from '@iconify/vue';
import { read, utils } from 'xlsx'
import DashLayout from '@/Layouts/DashLayout.vue';
const page = usePage()

const formTambah = ref(false)
const formImpor = ref(false)
const selectedMapel = ref('')
const loading = ref(false)
const mapels = ref([])
const formMode = ref('add')
const tambah = (mapel) => {
    selectedMapel.value = mapel
    newTps.value[0].mapel_id = mapel.kode
    formMode.value = 'add'
    formTambah.value = true
}

const closeForm = () => {
    selectedMapel.value = ''
    newTp.value = {}
    formTambah.value = false
}

const newTp = ref({})

const role = computed(() => page.props.auth.roles[0])

const ekskuls = ref([])

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
    loading.value = true
    const file = e.target.files[0]
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    const ws = wb.Sheets[wb.SheetNames[0]];
    let datas = utils.sheet_to_json(ws)
    if (['superadmin', 'admin'].includes(role.value)) {
        imporTp(datas)
    } else if (role.value == 'admin_tp') {
        newTps.value = datas
    }

    loading.value = false
    // await router.post(route('dashboard.pembelajaran.tp.impor'), {tps: datas}, {
    //     onStart: () => loading.value = true,
    //     onSuccess: (page) => {
    //         ElNotification({title: 'Info', message: page.props.flash.message, type:'success'})
    //         router.reload({only: ['mapels']})
    //     },
    //     onError: errs => {
    //         Object.keys(errs).forEach(k => {
    //             const msg = errs[k].toLowerCase().includes('undefined array key') ? 'Kolom ' + errs[k].split('"')[1] + ' Kosong' : errs[k]
    //             setTimeout(() => {
    //                 ElNotification({title: 'Error', message: msg, type:'error'})
    //             }, 500)
    //         })
    //     },
    //     onFinish: () => loading.value = false
    // })
}

const imporTp = async(tps) => {
    await router.post(route('dashboard.pembelajaran.tp.impor'), {tps: tps}, {
        onStart: () => loading.value = true,
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: page.props.flash.message, type:'success'})
            router.reload({only: ['mapels']})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                const msg = errs[k].toLowerCase().includes('undefined array key') ? 'Kolom ' + errs[k].split('"')[1] + ' Kosong' : errs[k]
                setTimeout(() => {
                    ElNotification({title: 'Error', message: msg, type:'error'})
                }, 500)
            })
        },
        onFinish: () => loading.value = false
    })
}

const onFileMapelPicked = async(e) => {
    // console.log(mapel)
    const file = e.target.files[0]
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    const ws = wb.Sheets[wb.SheetNames[0]];
    let datas = utils.sheet_to_json(ws)
    await router.post(route('dashboard.pembelajaran.mapel.impor'), {datas: datas}, {
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
const onFileEkskulPicked = async(e) => {
    // console.log(mapel)
    const file = e.target.files[0]
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    const ws = wb.Sheets[wb.SheetNames[0]];
    let datas = utils.sheet_to_json(ws)
    await router.post(route('dashboard.pembelajaran.ekskul.impor'), {datas: datas}, {
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
    formMode.value = 'edit'
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

const assignMapel = () => {
    console.log(mapels.value)
    router.post(route('dashboard.pembelajaran.mapel.assign'), {sekolahId: page.props.sekolahs[0].id, mapels: mapels.value.filter(m => m !== null)}, {
        onSuccess: page => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500)
            })
        }
    })
}

const assignEkskul = () => {
    router.post(route('dashboard.pembelajaran.ekskul.assign'), {sekolahId: page.props.sekolahs[0].id, ekskuls: ekskuls.value.filter(e => e )}, {
        onSuccess: page => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500)
            })
        }
    })
}

const newTps = ref([
    {
        fase: "A",
        tingkat: "1",
        kode: "",
        elemen: "",
        semester: "",
        agama: "",
        teks: ""
    }
])

const onDialogClosed = () => {
    newTps.value = [{
        fase: "A",
        tingkat: "1",
        kode: "",
        elemen: "",
        semester: "",
        agama: "",
        teks: ""
    }]
}

// const tes = () => {
//     imporTp(newTps.value)
// }

const addRow = () => {
    newTps.value.push(
        {
            fase: "A",
            tingkat: "1",
            mapel_id: selectedMapel.value.kode,
            kode: "",
            elemen: "",
            semester: "",
            agama: "",
            teks: ""
        }
    )
} 

onBeforeMount(() => {
    mapels.value = page.props.sekolahs[0].mapels ? page.props.sekolahs[0].mapels.map(mapel => mapel.id) : []
    ekskuls.value = page.props.sekolahs[0].ekskuls ? page.props.sekolahs[0].ekskuls.map(ekskul => ekskul.id) : []
})
</script>

<template>
<Head title="Pembelajaran" />
<DashLayout>
    <template #header>
        Pembelajaran
    </template>
    <el-card shadow="never" v-loading="loading">
        <template #header>
            <span class="title font-bold text-lg">Konten Pembelajaran</span>
        </template>
        <div class="card-body">
            <!-- {{ mapels }} -->
            <el-row>
                <el-col>
                    <div class="card border">
                        <div class="card-header bg-slate-100 p-2 flex items-center justify-between">
                            <h3 class="font-bold">Mata Pelajaran</h3>
                            <span>
                                <!-- {{ page.props.mapels }} -->
                                <el-popover trigger="click" width="350" v-if="page.props.mapels.length > 0">
                                    <template #reference>
                                        <el-button size="small" type="success" :disabled="role !== 'ops'">Atur Mapel</el-button>
                                    </template>
                                    <template #default>
                                        <h3 class="text-sky-600 font-bold">Pilih Mapel</h3>
                                        <ol>
                                            <li v-for="(mapel,m) in page.props.mapels" :key="mapel.id">
                                                <el-checkbox :label="mapel.label" v-model="mapels[m]" :true-value="mapel.id">{{ mapel.label }}</el-checkbox>
                                            </li>
                                        </ol>
                                        <el-button type="success" @click="assignMapel" >Simpan</el-button>
                                    </template>
                                </el-popover>
                                <el-button size="small" type="primary" @click="$refs.fileMapel.click()" :disabled="role !== 'admin'">Impor Mapel</el-button>
                                <el-button size="small" type="primary" @click="$refs.filElemen.click()" :disabled="role !== 'admin'">Impor Elemen</el-button>
                                <el-button type="success" size="small" @click="$refs.fileTp.click()" :disabled="role !== 'admin'">Impor TP</el-button>
                                <input type="file" ref="filElemen" accept=".xls,.xlsx,.ods,.csv" class="hidden" @change="onFileElemenPicked" />
                                <input type="file" ref="fileTp" accept=".xls,.xlsx,.ods, .csv" class="hidden" @change="onFileTpPicked" />
                                <input type="file" ref="fileMapel" accept=".xls,.xlsx,.ods, .csv" class="hidden" @change="onFileMapelPicked" />
                            </span>
                        </div>
                        <div class="card-body p-2">
                            <!-- {{ page.props.mapels }} -->
                            <div class="data-mapel" v-if="(page.props.sekolahs[0].mapels.length > 0 && role !== 'admin') || (page.props.mapels.length > 0 && ['superadmin', 'admin', 'admin_tp'].includes(role))">
                                <template v-for="(mapel, m) in ['superadmin', 'admin', 'admin_tp'].includes(role) ? page.props.mapels : page.props.sekolahs[0].mapels" :key="m">
                                    <el-collapse>
                                        <el-collapse-item>
                                            <template #title>
                                                <h3>{{ m+1 }}. {{ mapel.label }}</h3>
                                            </template>
                                            <div class="collapse-body p-2 bg-sky-100">
                                                <div class="collapse-body--tile flex justify-between mb-2">
                                                        <h4 class="font-bold text-slate-600">Data Tujuan Pembelajaran</h4>
                                                        <el-button-group>
                                                            <el-button type="primary" size="small" @click="tambah(mapel)" :disabled="!['superadmin', 'admin', 'admin_tp'].includes(role)">Tambah TP</el-button>
                                                            
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
                            <div v-else>
                                <!-- {{ page.props.mapels }} -->
                                <el-alert title="Atur Mapel Seperti video ini" type="warning">
                                    <video controls width="800" autoplay>
                                        <source src="/videos/atur-mapel.mp4" type="video/mp4">
                                    </video>
                                </el-alert>
                            </div>
                        </div>
                    </div>
                </el-col>
            </el-row>
            <el-row class="my-4">
                <el-col>
                    <div class="card border">
                        <div class="card-header flex items-center justify-between p-2 bg-slate-100">
                            <div class="card-header--title">
                                <h3 class="font-bold">Ekstrakurikuler</h3>
                            </div>
                            <div class="card-header--items">
                                <el-popover trigger="click" width="350">
                                    <template #reference>
                                        <el-button size="small" type="primary" :disabled="role !== 'ops'">Atur Ekskul</el-button>
                                    </template>
                                    <template #default>
                                        <h3 class="text-sky-600 font-bold">Pilih Ekskul</h3>
                                        <ol>
                                            <li v-for="(ekskul,e) in page.props.ekskuls" :key="ekskul.id">
                                                <el-checkbox :label="ekskul.nama" v-model="ekskuls[e]" :true-value="ekskul.id">{{ ekskul.nama }}</el-checkbox>
                                            </li>
                                        </ol>

                                        <el-button type="success" @click="assignEkskul" >Simpan</el-button>
                                    </template>
                                </el-popover>
                                <el-button type="success" size="small" @click="$refs.fileEkskul.click()" :disabled="role !== 'admin'">Impor Ekskul</el-button>
                                <input type="file" ref="fileEkskul" accept=".xls,.xlsx,.ods,.csv" class="hidden" @change="onFileEkskulPicked" />
                            </div>
                        </div>
                        <div class="card-body">
                            <el-table :data="page.props.sekolahs[0].ekskuls">
                                <el-table-column label="Kode" prop="kode"></el-table-column>
                                <el-table-column label="Nama" prop="nama"></el-table-column>
                                <el-table-column label="Keterangan" prop="keterangan"></el-table-column>
                            </el-table>
                            <!-- {{ page.props.sekolahs[0].ekskuls }} -->
                        </div>
                    </div>
                </el-col>
            </el-row>
        </div>
    </el-card>

    <el-dialog class="form-tambah" v-model="formTambah" :show-close="false" @close="closeForm" :width="formMode == 'edit' ? '60%' : '100%'" :fullscreen="formMode == 'add'" body-class="" @closed="onDialogClosed">
        <template #header="{close}" >
            <span class="flex items-center justify-between" >
                <h3 class="text-sky-800 text-lg">Tambah <span class="font-black">TP</span> <small>{{ selectedMapel.label }}</small></h3>
                <div class="flex items-center gap-1">
                    <el-button type="success" @click="$refs.fileTp.click()" :disabled="role !== 'admin_tp'">Impor TP</el-button>
                    <el-button type="primary" @click="addRow">
                        <Icon icon="mdi:plus" />
                    </el-button>
                    <el-button type="primary" @click="imporTp(newTps)" :loading="loading">Simpan</el-button>
                    <el-button type="danger" size="small" circle @click="close">
                        <Icon icon="mdi-close"  />
                    </el-button>
                </div>
            </span>
        </template>
        <el-card class="dialog-body" v-loading="loading" style="height: 90vh;">
            <el-form v-model="newTp" label-position="top" v-if="formMode == 'edit'">
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
            <el-scrollbar max-height="90vh" v-if="formMode == 'add'">
                <table class="w-full" >
                    <thead>
                        <tr>
                            <th class="border bg-slate-100 py-1 w-[50px]">No</th>
                            <th class="border bg-slate-100 py-1 w-[150px]" v-if="selectedMapel.kode == 'pabp'">Agama</th>
                            <th class="border bg-slate-100 py-1 w-[70px]">Fase</th>
                            <th class="border bg-slate-100 py-1 w-[80px]">Tingkat</th>
                            <th class="border bg-slate-100 py-1 w-[80px]">Semester</th>
                            <th class="border bg-slate-100 py-1 w-[300px]">Elemen</th>
                            <th class="border bg-slate-100 py-1 w-[200px]">Kode</th>
                            <th class="border bg-slate-100 py-1">Teks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(tp, t) in newTps" :key="t">
                            <tr>
                                <td class="text-center border px-1 py-2 align-top">{{ t+1 }}</td>
                                <td class="text-center border px-1 py-2 align-top" v-if="selectedMapel.kode == 'pabp'">
                                    <el-select v-model="tp.agama" placeholder="Pilih Agama">
                                        <el-option v-for="agama in ['Islam','Kristen','Katolik','Hindu','Budha','Konghuchu']" :key="agama" :value="agama"></el-option>
                                    </el-select>
                                </td>
                                <td class="text-center border px-1 py-2 align-top">
                                    <el-select v-model="tp.fase" placeholder="Fase">
                                        <el-option v-for="fase in ['A','B','C']" :key="fase" :value="fase"></el-option>
                                    </el-select>
                                </td>
                                <td class="text-center border px-1 py-2 align-top">
                                    <el-select v-model="tp.tingkat" placeholder="Kelas">
                                        <el-option v-for="t of 6" :key="t" :value="t"  />
                                    </el-select>
                                </td>
                                <td class="text-center border px-1 py-2 align-top">
                                    <el-select v-model="tp.semester" placeholder="Pilih Semester">
                                        <el-option v-for="s of 2" :key="`s${s}`" :value="s"  />
                                    </el-select>
                                </td>
                                <td class="text-center border px-1 py-2 align-top">
                                    <el-select v-model="tp.elemen" placeholder="Pilih Elemen">
                                        <el-option v-for="(elemen, e) in page.props.elemens.filter(el => el.mapel_id == selectedMapel.kode)" :key="e" :value="elemen.nama" :label="`${selectedMapel.kode == 'pabp' ? elemen.agama +' |' : ''} ${elemen.nama}`" />
                                    </el-select>
                                </td>
                                <td class="text-center border px-1 py-2 align-top">
                                    <el-input v-model="tp.kode" placeholder="Kode TP"></el-input>
                                </td>
                                <td class="text-center border px-1 py-2 align-top">
                                    <el-input type="textarea" placeholder="Teks Tujuan Pembelajaran" v-model="tp.teks" rows="1" autosize></el-input>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </el-scrollbar>
        </el-card>
    </el-dialog>
</DashLayout>
</template>

<style>
.el-dialog__header {
    background: rgb(208, 228, 247);
    padding: 10px;
}
</style>