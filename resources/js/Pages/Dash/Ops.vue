<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import DashLayout from '@/Layouts/DashLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ElCard, ElNotification } from 'element-plus'
import { Icon } from '@iconify/vue'

const page = usePage()

const formImpor = ref(false)
const FormImpor = defineAsyncComponent(() => import('@/Components/Dashboard/FormImpor.vue'))
const formOps = ref(false)
const FormOps = defineAsyncComponent(() => import('@/Components/Dashboard/Sekolah/FormOps.vue'))
const search = ref('')
const opss = computed(() => {
    // return page.props.ops.filter(ops => ops.nama.toLowerCase().includes(search.value.toLowerCase()))
    return page.props.ops
})

const closeForm = () => {
    formOps.value = false
    selectedOps.value = null
    router.reload({only: ['ops']})
}

const closeImpor = () => {
    formImpor.value = false
    router.reload({only: ['ops']})
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
    'agama',
    'jabatan'])

const selectedOps = ref(null)
const edit = (item) => {
    selectedOps.value = item
    formOps.value = true
}

const hapus = async(id) => {
    await router.delete(route('dashboard.ops.destroy', {id: id}), {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Data Ops dihapus', type: 'success'})
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

const createAccount = async(id) => {
    router.post(route('dashboard.ops.account.add'), {id: id}, {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Akun Berhasil dibuat', type: 'success'})
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
    <Head title="Data Operator" />

    <DashLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">{{ page.props.auth.roles[0] !== 'admin' ? page.props.sekolahs[0]?.nama : 'Admin' }}</h2>
        </template>
        <div class="page">
            <el-card>
                <template #header>
                    <div class="card-toolbar flex items-center justify-between">
                        <div class="card-title flex items-center ">
                            <Icon icon="mdi:caccount-tie" class="mb-1" />
                            <span>Data Operator</span>
                        </div>
                        <div class="card-toolbar--items flex items-center gap-1 ">
                            <el-input v-model="search" placeholder="Cari Ops" clearable>
                                <template #suffix>
                                    <Icon icon=mdi:magnify />
                                </template>
                            </el-input>
                            <el-button-group class="flex-grow w-[300px]" >
                                <el-button type="primary" @click="formOps = true" :disabled="!page.props.auth.can.includes('add_guru')">
                                    <Icon icon="mdi-plus" />
                                    Baru
                                </el-button>
                                <el-button type="success" @click="formImpor = true" :disabled="!page.props.auth.can.includes('add_guru')">
                                    <Icon icon="mdi-file-excel" />
                                    Impor
                                </el-button>
                            </el-button-group>
                        </div>
                    </div>
                </template>
                <el-table :data="opss" height="420px" size="small">
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
                    <el-table-column label="Nama Ops">
                        <template #default="scope">
                            <p>{{ scope.row.gelar_depan }} {{ scope.row.nama }}, {{ scope.row.gelar_belakang }}</p>
                        </template>
                    </el-table-column>
                    <el-table-column prop="status" label="Status" />
                    <el-table-column prop="alamat" label="Alamat" />
                    <!-- <el-table-column label="Akun" >
                        <template #default="scope">
                            {{ scope.row.user }}
                        </template>
                    </el-table-column> -->
                    <el-table-column prop="hp" label="Nomor HP" />
                    <el-table-column label="Opsi">
                        <template #default="scope">
                            <div class="flex items-center gap-1">
                                <span>
                                    <el-popconfirm v-if="!scope.row.user" size="small" :title="`Buatkan akun untuk ${scope.row.nama}?`" @confirm="createAccount(scope.row.id)">
                                        <template #reference>
                                            <el-button circle type="primary" size="small">
                                                <Icon icon="mdi:account-plus-outline" />
                                            </el-button>
                                        </template>
                                    </el-popconfirm>
                                    <el-popconfirm v-else size="small" :title="`Reset Password ${scope.row.nama}?`" @confirm="createAccount(scope.row.id)">
                                        <template #reference>
                                            <el-button circle type="warning" size="small" :disabled="!page.props.auth.can.includes('update guru')">
                                                <Icon icon="mdi:account-reactivate" />
                                            </el-button>
                                        </template>
                                    </el-popconfirm>
                                </span>
                                <el-popconfirm size="small" :title="`Yakin menghapus data ${scope.row.nama}?`" @confirm="hapus(scope.row.id)" >
                                    <template #reference>
                                        <el-button circle type="danger" size="small" :disabled="!page.props.auth.can.includes('delete guru')">
                                            <Icon icon="mdi:delete" />
                                        </el-button>
                                    </template>
                                </el-popconfirm>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </el-card>

            <!-- p>lorem*10 -->
        </div>
        <FormOps :open="formOps" @close="closeForm" :selectedOps="selectedOps" v-if="formOps" />
        <FormImpor :open="formImpor" @close="closeImpor" :fields="fields" v-if="formImpor" url="dashboard.ops.impor" title="Ops" />
    </DashLayout>
</template>
