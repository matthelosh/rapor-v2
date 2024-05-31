<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DashLayout from '@/Layouts/DashLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ElCard, ElNotification } from 'element-plus'
import { Icon } from '@iconify/vue'

const page = usePage()

const formImpor = ref(false)
const FormImpor = defineAsyncComponent(() => import('@/Components/Dashboard/FormImpor.vue'))
const formSekolah = ref(false)
const FormSekolah = defineAsyncComponent(() => import('@/Components/Dashboard/Sekolah/FormSekolah.vue'))
const search = ref('')
const sekolahs = computed(() => {
    return page.props.sekolahs.filter(sekolah => sekolah.nama.toLowerCase().includes(search.value.toLowerCase()))
})

const closeForm = () => {
    formSekolah.value = false
    selectedSekolah.value = null
    router.reload({only: ['sekolahs']})
}

const closeImpor = () => {
    formImpor.value = false
    router.reload({only: ['sekolahs']})
}
const fields = ref([ 
        'npsn',
        'nama',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'kode_pos',
        'telp',
        'email',
        'website',
        'nama_ks',
        'nip_ks'])

const selectedSekolah = ref(null)
const edit = (item) => {
    selectedSekolah.value = item
    formSekolah.value = true
}

const hapus = async(id) => {
    await router.delete(route('dashboard.sekolah.destroy', {id: id}), {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Data Sekolah dihapus', type: 'success'})
        },
        onError: err => {
            Object.keys(err).forEach(k => {
                setTimeout(() => {
                    ElNotification({ title: 'Error', message: err[k], type: 'error'})
                }, 500)
            })
        }
    })
}

const addOps = async(id) => {
    // alert(id)
    await router.post(route('dashboard.sekolah.ops.add', {id: id}),null, {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Data Operator dibuat', type: 'success'})
        },
        onError: err => {
            Object.keys(err).forEach(k => {
                setTimeout(() => {
                    ElNotification({ title: 'Error', message: err[k], type: 'error'})
                }, 500)
            })
        }
    })
}
</script>
<template>
    <Head title="Data Sekolah" />

    <DashLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Sekolah</h2>
        </template>

        <div class="page">
            <el-card>
                <template #header>
                    <div class="card-toolbar flex items-center justify-between">
                        <div class="card-title flex items-center ">
                            <Icon icon="mdi:city-variant" class="mb-1" />
                            <span>Data Sekolah</span>
                        </div>
                        <div class="card-toolbar--items flex items-center gap-1 ">
                            <el-input v-model="search" placeholder="Cari Sekolah" clearable>
                                <template #suffix>
                                    <Icon icon=mdi:magnify />
                                </template>
                            </el-input>
                            <el-button-group class="flex-grow w-[300px]">
                                <el-button type="primary" @click="formSekolah = true" :disabled="!page.props.auth.can.includes('add school')">
                                    <Icon icon="mdi-plus" />
                                    Baru
                                </el-button>
                                <el-button type="success" @click="formImpor = true" :disabled="!page.props.auth.can.includes('add school')">
                                    <Icon icon="mdi-file-excel" />
                                    Impor
                                </el-button>
                            </el-button-group>
                        </div>
                    </div>
                </template>
                <el-table :data="sekolahs" height="420px" size="small">
                    <el-table-column label="Logo">
                        <template #default="scope">
                            <img :src="scope.row.logo" class="w-10" />
                            <!-- {{ scope.row }} -->
                            <!-- {{ scope.row.logo === null }} -->
                        </template>
                    </el-table-column>
                    <el-table-column  label="NPSN" >
                        <template #default="scope">
                            <el-button type="primary" text size="small" @click="edit(scope.row)">{{ scope.row.npsn }}</el-button>
                        </template>
                    </el-table-column>
                    <el-table-column prop="nama" label="Nama Sekolah" />
                    <el-table-column prop="alamat" label="Alamat" />
                    <el-table-column prop="desa" label="Desa" />
                    <el-table-column prop="nama_ks" label="Kepala Sekolah" />
                    <el-table-column label="Opsi">
                        <template #default="scope">
                            <span class="flex items-center gap-1">
                                        <el-button circle type="primary" size="small" @click="addOps(scope.row.id)" :disabled="!page.props.auth.can.includes('add guru')">
                                            <Icon icon="mdi:laptop-account" />
                                        </el-button>
                            <el-popconfirm size="small" :title="`Yakin menghapus data ${scope.row.nama}?`" @confirm="hapus(scope.row.id)">
                                <template #reference>
                                    
                                        <el-button circle type="danger" size="small" :disabled="!page.props.auth.can.includes('delete school')">
                                            <Icon icon="mdi:delete" />
                                        </el-button>
                                    
                                </template>
                            </el-popconfirm>
                        </span>
                        </template>
                    </el-table-column>
                </el-table>
            </el-card>

            <!-- p>lorem*10 -->
        </div>
        <FormSekolah :open="formSekolah" @close="closeForm" :selectedSekolah="selectedSekolah" v-if="formSekolah" />
        <FormImpor :open="formImpor" @close="closeImpor" :fields="fields" v-if="formImpor" title="Sekolah" url="dashboard.sekolah.impor" />
    </DashLayout>
</template>
