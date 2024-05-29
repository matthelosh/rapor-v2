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
const formGuru = ref(false)
const FormGuru = defineAsyncComponent(() => import('@/Components/Dashboard/Guru/FormGuru.vue'))
const search = ref('')
const gurus = computed(() => {
    return page.props.gurus.filter(sekolah => sekolah.nama.toLowerCase().includes(search.value.toLowerCase()))
})

const closeForm = () => {
    formGuru.value = false
    selectedGuru.value = null
    router.reload({only: ['gurus']})
}

const closeImpor = () => {
    formImpor.value = false
    router.reload({only: ['gurus']})
}
const fields = ref([ 
    'nip',
    'gelar_depan',
    'nama',
    'gelar_belakang',
    'jk',
    'alamat',
    'hp',
    'status',
    'pangkat',
    'email',
    'foto',
    'agama',
    'jabatan'])

const selectedGuru = ref(null)
const edit = (item) => {
    selectedGuru.value = item
    formGuru.value = true
}

const hapus = async(id) => {
    await router.delete(route('dashboard.sekolah.destroy', {id: id}), {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Data Guru dihapus', type: 'success'})
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
    <Head title="Data Guru" />

    <DashLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Guru</h2>
        </template>

        <div class="page">
            <el-card>
                <template #header>
                    <div class="card-toolbar flex items-center justify-between">
                        <div class="card-title flex items-center ">
                            <Icon icon="mdi:caccount-tie" class="mb-1" />
                            <span>Data Guru</span>
                        </div>
                        <div class="card-toolbar--items flex items-center gap-1 ">
                            <el-input v-model="search" placeholder="Cari Guru" clearable>
                                <template #suffix>
                                    <Icon icon=mdi:magnify />
                                </template>
                            </el-input>
                            <el-button-group class="flex-grow w-[300px]">
                                <el-button type="primary" @click="formGuru = true">
                                    <Icon icon="mdi-plus" />
                                    Baru
                                </el-button>
                                <el-button type="success" @click="formImpor = true">
                                    <Icon icon="mdi-file-excel" />
                                    Impor
                                </el-button>
                            </el-button-group>
                        </div>
                    </div>
                </template>
                <el-table :data="gurus" height="420px" size="small">
                    <el-table-column label="Foto">
                        <template #default="scope">
                            <img :src="scope.row.foto" class="w-10" />
                            <!-- {{ scope.row }} -->
                            <!-- {{ scope.row.logo === null }} -->
                        </template>
                    </el-table-column>
                    <el-table-column  label="NIP" >
                        <template #default="scope">
                            <el-button type="primary" text size="small" @click="edit(scope.row)">{{ scope.row.nip }}</el-button>
                        </template>
                    </el-table-column>
                    <el-table-column label="Nama Guru">
                        <template #default="scope">
                            <p>{{ scope.row.gelar_depan }} {{ scope.row.nama }}, {{ scope.row.gelar_belakang }}</p>
                        </template>
                    </el-table-column>
                    <el-table-column prop="status" label="Status" />
                    <el-table-column prop="alamat" label="Alamat" />
                    <el-table-column prop="hp" label="Nomor HP" />
                    <el-table-column label="Opsi">
                        <template #default="scope">
                            <el-popconfirm size="small" :title="`Yakin menghapus data ${scope.row.nama}?`" @confirm="hapus(scope.row.id)">
                                <template #reference>
                                    <el-button circle type="danger" size="small">
                                        <Icon icon="mdi:delete" />
                                    </el-button>
                                </template>
                            </el-popconfirm>
                        </template>
                    </el-table-column>
                </el-table>
            </el-card>

            <!-- p>lorem*10 -->
        </div>
        <FormGuru :open="formGuru" @close="closeForm" :selectedGuru="selectedGuru" v-if="formGuru" />
        <FormImpor :open="formImpor" @close="closeImpor" :fields="fields" v-if="formImpor" />
    </DashLayout>
</template>
