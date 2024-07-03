<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DashLayout from '@/Layouts/DashLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ElCard, ElNotification } from 'element-plus'
import { Icon } from '@iconify/vue'
import { groupBy } from 'lodash'
import { avatar } from '@/helpers/Gambar.js'
import { utils, writeFile } from 'xlsx'

const page = usePage()

const formImpor = ref(false)
const FormImpor = defineAsyncComponent(() => import('@/Components/Dashboard/FormImpor.vue'))
const formGuru = ref(false)
const FormGuru = defineAsyncComponent(() => import('@/Components/Dashboard/Guru/FormGuru.vue'))
const search = ref('')
const gurus = computed(() => {
    // let datas = groupBy(page.props.gurus, 'sekolas')
    return page.props.gurus.filter(guru => {
        return guru.nama.toLowerCase().includes(search.value.toLowerCase())
    })
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
    'agama',
    'jabatan'])

const selectedGuru = ref(null)
const edit = (item) => {
    selectedGuru.value = item
    formGuru.value = true
}

const hapus = async(id) => {
    await router.delete(route('dashboard.guru.destroy', {id: id}), {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
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
    router.post(route('dashboard.guru.account.add'), {id: id}, {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
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

const unduhFormat = async() => {
    let data = [
        {
            nip : '',
            nuptk: '',
            gelar_depan: '',
            nama: '',
            gelar_belakang: '',
            jk: '',
            alamat: '',
            hp: '',
            status: '',
            email: '',
            agama: '',
            pangkat: '',
            jabatan: ''
        }
    ]

    const ws = utils.json_to_sheet(data)
    const wb = utils.book_new()
    utils.book_append_sheet(wb, ws, "ORTU")
    writeFile(wb, "Format Impor Guru "+page.props.sekolahs[0].nama+".xlsx")
}
</script>
<template>
    <Head title="Data Guru" />

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
                            <span class="uppercase">Data Guru {{ page.props.auth.roles[0] !== 'admin' ? page.props.sekolahs[0]?.nama : 'Semua Sekolah' }}</span>
                        </div>
                        <div class="card-toolbar--items flex items-center gap-1 px-2 w-[50%]">
                            <el-button-group class="flex-grow w-[500px]">
                                <el-button type="primary" @click="formGuru = true">
                                    <Icon icon="mdi-plus" />
                                    Baru
                                </el-button>
                                <el-button type="warning" @click="unduhFormat">
                                    <Icon icon="mdi-file-download" />
                                    Unduh Format
                                </el-button>
                                <el-button type="success" @click="formImpor = true">
                                    <Icon icon="mdi-file-excel" />
                                    Impor
                                </el-button>
                            </el-button-group>
                            <el-input v-model="search" placeholder="Cari Guru Berdasarkan Nama" clearable >
                                <template #suffix>
                                    <Icon icon=mdi:magnify />
                                </template>
                            </el-input>
                        </div>
                    </div>
                </template>
                <el-table :data="gurus" height="80vh" size="small" :default-sort="{ prop: 'sekolahs', order: 'descending' }">
                    <el-table-column label="Foto" width="60">
                        <template #default="scope">
                            <img :src="avatar(scope.row)" class="w-10" />
                        </template>
                    </el-table-column>
                    <el-table-column label="Sekolah" v-if="page.props.auth.roles.includes('admin')">
                        <template #default="scope">
                            <div>
                                <ul class="list-decimal">
                                    <li v-for="(sekolah, s) in scope.row.sekolahs.map(sk => sk.nama)" :key="s">{{ sekolah }}</li>

                                </ul>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column  label="NIP" width="150" :fixed="true">
                        <template #default="scope">
                            <el-button type="primary" text size="small" @click="edit(scope.row)">{{ scope.row.nip }}</el-button>
                        </template>
                    </el-table-column>
                    <el-table-column label="Nama Guru">
                        <template #default="scope">
                            <p>{{ scope.row.gelar_depan }} {{ scope.row.nama }}, {{ scope.row.gelar_belakang }}</p>
                        </template>
                    </el-table-column>
                    <el-table-column prop="status" label="Status" width="65" />
                    <el-table-column prop="alamat" label="Alamat" />
                    <el-table-column label="Jabatan" width="100">
                        <template #default="scope">
                            {{ scope.row.jabatan }}
                        </template>
                    </el-table-column>
                    <el-table-column prop="hp" label="Nomor HP" width="150" />
                    <el-table-column label="Opsi" width="80">
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
        <FormGuru :open="formGuru" @close="closeForm" :selectedGuru="selectedGuru" v-if="formGuru" />
        <FormImpor :open="formImpor" @close="closeImpor" :fields="fields" v-if="formImpor" url="dashboard.guru.impor" title="Guru" />
    </DashLayout>
</template>
