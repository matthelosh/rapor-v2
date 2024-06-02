<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DashLayout from '@/Layouts/DashLayout.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ElCard, ElNotification } from 'element-plus'
import { Icon } from '@iconify/vue'
import { groupBy } from 'lodash'

const page = usePage()

const formRombel = ref(false)
const FormRombel = defineAsyncComponent(() => import('@/Components/Dashboard/Rombel/FormRombel.vue'))
const rombelSiswa = ref(false)
const RombelSiswa = defineAsyncComponent(() => import('@/Components/Dashboard/Rombel/RombelSiswa.vue'))
const search = ref('')
const rombels = computed(() => {
    return page.props.rombels.filter(rombel => {
        return rombel.label.toLowerCase().includes(search.value.toLowerCase())
    })
})

const closeForm = () => {
    formRombel.value = false
    selectedRombel.value = null
    router.reload({only: ['rombels']})
}

const selectedRombel = ref(null)
const edit = (item) => {
    selectedRombel.value = item
    formRombel.value = true
}

const mgmSiswa = (item) => {
    selectedRombel.value = item
    rombelSiswa.value = true
}
const closeMgmSiswa = () => {
    rombelSiswa.value = false
    selectedRombel.value = null
}

const hapus = async(id) => {
    await router.delete(route('dashboard.rombel.destroy', {id: id}), {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Data Rombel dihapus', type: 'success'})
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">{{ page.props.auth.roles[0] !== 'admin' ? page.props.sekolahs[0]?.nama : 'Admin' }}</h2>
        </template>
        <div class="page">
            <el-card>
                <template #header>
                    <div class="card-toolbar flex items-center justify-between">
                        <div class="card-title flex items-center ">
                            <Icon icon="mdi:caccount-tie" class="mb-1" />
                            <span class="uppercase">Data Rombel/Kelas {{ page.props.auth.roles[0] !== 'admin' ? page.props.sekolahs[0]?.nama : 'Semua Sekolah' }}</span>
                        </div>
                        <div class="card-toolbar--items flex items-center gap-1 ">
                            <el-input v-model="search" placeholder="Cari Rombel Berdasarkan Label" clearable>
                                <template #suffix>
                                    <Icon icon=mdi:magnify />
                                </template>
                            </el-input>
                            <el-button-group class="flex-grow">
                                <el-button type="primary" @click="formRombel = true">
                                    <Icon icon="mdi-plus" />
                                    Baru
                                </el-button>
                            </el-button-group>
                        </div>
                    </div>
                </template>
                <el-table :data="rombels" height="420px" size="small" :default-sort="{ prop: 'label', order: 'descending' }">
                    <el-table-column label="Sekolah" v-if="page.props.auth.roles.includes('admin')">
                        <template #default="scope">
                            <div>
                                {{ scope.row.sekolah.nama }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column  label="Kode" >
                        <template #default="scope">
                            <el-button type="primary" text size="small" @click="edit(scope.row)">{{ scope.row.kode }}</el-button>
                        </template>
                    </el-table-column>
                    <el-table-column label="Label" prop="label" />
                        
                    <el-table-column label="Wali Kelas">
                        <template #default="scope">
                            <p>{{ scope.row.guru.gelar_depan }} {{ scope.row.guru.nama }}, {{ scope.row.guru.gelar_belakang }}</p>
                        </template>
                    </el-table-column>
                    <el-table-column label="Siswa">
                        <template #default="scope">
                            <div>
                                <span>Lk: {{ scope.row.siswas?.filter(sa=>sa.jk=='Laki-laki').length }}</span>
                                <span>Pr: {{ scope.row.siswas?.filter(sa=>sa.jk=='Perempuan').length }}</span>
                                <span>Jml: {{ scope.row.siswas?.length }}</span>
                            </div>
                        </template>    
                    </el-table-column>
                    <el-table-column  label="Status" >
                        <template #default="scope">
                            <Icon :icon="`mdi:${scope.row.is_active == '1' ? 'check-circle' : 'close-circle'}`" :class="scope.row.is_active == '1' ? 'text-green-600' : 'text-red-600'" class="text-xl" />
                        </template>    
                    </el-table-column>
                    <el-table-column label="Opsi">
                        <template #default="scope">
                            <div class="flex items-center gap-1">
                                <span>
                                    <el-tooltip size="small" :content="`Masukkan Siswa ke ${scope.row.label}?`" placement="left">
                                            <el-button circle type="primary" size="small"  @click="mgmSiswa(scope.row)">
                                                <Icon icon="mdi:account-plus-outline" />
                                            </el-button>
                                    </el-tooltip>
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

        </div>
        <FormRombel :open="formRombel" @close="closeForm" :selectedRombel="selectedRombel" v-if="formRombel" />
        <RombelSiswa :show=rombelSiswa @close="closeMgmSiswa" :selectedRombel="selectedRombel" v-if="rombelSiswa" />
        <!-- <FormImpor :open="formImpor" @close="closeImpor" :fields="fields" v-if="formImpor" url="dashboard.guru.impor" title="Guru" /> -->
    </DashLayout>
</template>
