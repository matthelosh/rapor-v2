<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import DashLayout from '@/Layouts/DashLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ElCard, ElNotification } from 'element-plus'
import { Icon } from '@iconify/vue'
import { groupBy } from 'lodash'

const page = usePage()

const formImpor = ref(false)
const FormImpor = defineAsyncComponent(() => import('@/Components/Dashboard/FormImpor.vue'))
const formSiswa = ref(false)
const FormSiswa = defineAsyncComponent(() => import('@/Components/Dashboard/Siswa/FormSiswa.vue'))
const search = ref('')
const siswas = computed(() => {
    // let datas = groupBy(page.props.siswas, 'sekolas')
    return page.props.siswas.filter(siswa => {
        return siswa.nama.toLowerCase().includes(search.value.toLowerCase())
    })
})

const closeForm = () => {
    formSiswa.value = false
    selectedSiswa.value = null
    router.reload({only: ['siswas']})
}

const closeImpor = () => {
    formImpor.value = false
    router.reload({only: ['siswas']})
}
const fields = ref([ 
'nisn',
    'nis',
    'nik',
    'nama',
    'jk',
    'alamat',
    'hp',
    'email',
    'foto',
    'agama',
    'angkatan',
    'sekolah_id'])

const selectedSiswa = ref(null)
const edit = (item) => {
    selectedSiswa.value = item
    formSiswa.value = true
}

const fotoUrl = (item) => {
    // console.log(item.foto)
    let foto = item.foto ? item.foto : (item.jk == 'Perempuan' ? (item.agama == 'Islam' ? '/img/siswi-is.png' : '/img/siswi.png') : '/img/siswa.png') 
    return foto
}

const hapus = async(id) => {
    await router.delete(route('dashboard.siswa.destroy', {id: id}), {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Data Siswa dihapus', type: 'success'})
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
    router.post(route('dashboard.siswa.account.add'), {id: id}, {
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
    <Head title="Data Siswa" />

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
                            <span class="uppercase">Data Siswa {{ page.props.auth.roles[0] !== 'admin' ? page.props.sekolahs[0]?.nama : 'Semua Sekolah' }}</span>
                        </div>
                        <div class="card-toolbar--items flex items-center gap-1 ">
                            <el-input v-model="search" placeholder="Cari Siswa Berdasarkan Nama" clearable>
                                <template #suffix>
                                    <Icon icon=mdi:magnify />
                                </template>
                            </el-input>
                            <el-button-group class="flex-grow w-[300px]">
                                <el-button type="primary" @click="formSiswa = true">
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
                <el-table :data="siswas" height="420px" size="small" :default-sort="{ prop: 'sekolahs', order: 'descending' }">
                    <el-table-column label="Foto">
                        <template #default="scope">
                            <img :src="fotoUrl(scope.row)" class="w-10" />
                        </template>
                    </el-table-column>
                    <el-table-column label="Sekolah" v-if="page.props.auth.roles.includes('admin')">
                        <template #default="scope">
                            <div>
                                {{ scope.row.sekolah?.nama }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column  label="NIS/NISN" >
                        <template #default="scope">
                            <el-button type="primary" text size="small" @click="edit(scope.row)">
                                {{ scope.row.nis ?? '-' }} / {{ scope.row.nisn }}    
                            </el-button>
                        </template>
                    </el-table-column>
                    <el-table-column label="Nama Siswa">
                        <template #default="scope">
                            <p>{{ scope.row.gelar_depan }} {{ scope.row.nama }}, {{ scope.row.gelar_belakang }}</p>
                        </template>
                    </el-table-column>
                    <el-table-column prop="jk" label="J. Kelamin" />
                    <el-table-column prop="agama" label="Agama" />
                    <el-table-column label="Kelas">
                        <template #default="scope">
                            {{scope.row.rombels[0]?.label}}
                        </template>
                    </el-table-column>
                    <el-table-column label="Akun" >
                        <template #default="scope">
                            {{ scope.row.user?.name }}
                        </template>
                    </el-table-column>
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
                                            <el-button circle type="warning" size="small">
                                                <Icon icon="mdi:account-reactivate" />
                                            </el-button>
                                        </template>
                                    </el-popconfirm>
                                </span>
                                <el-popconfirm size="small" :title="`Yakin menghapus data ${scope.row.nama}?`" @confirm="hapus(scope.row.id)">
                                    <template #reference>
                                        <el-button circle type="danger" size="small">
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
        <FormSiswa :open="formSiswa" @close="closeForm" :selectedSiswa="selectedSiswa" v-if="formSiswa" />
        <FormImpor :open="formImpor" @close="closeImpor" :fields="fields" v-if="formImpor" url="dashboard.siswa.impor" title="Siswa" />
    </DashLayout>
</template>
