<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

const page = usePage()
const props = defineProps({guguses: Array})

import DashLayout from '@/Layouts/DashLayout.vue';
import { ElNotification } from 'element-plus';

const loading = ref(false)
const gugus = ref({})
const showForm = ref(false)
const addGugus = () => {
    showForm.value = true
}
const closeForm = () => {
    showForm.value = false
    gugus.value = {}
}

const edit = (item) => {
    showForm.value = true
    gugus.value = item
}

const simpan = async() => {
    await router.post(route('dashboard.gugus.store'), gugus.value, {
        onSuccess: () => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
        }, 
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 1000)
            })
        },
        onFinish: () => {
            loading.value = false
            gugus.value = {}
            showForm.value = false
        }
    })
}

const hapus = async (id) => {
    router.delete(route('dashboard.gugus.destroy', {id: id}), {
        onSuccess: () => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
        }, 
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 1000)
            })
        },
        onFinish: () => {
            loading.value = false
            gugus.value = {}
            showForm.value = false
        }
    })
}

</script>

<template>
    <Head title="Data Gugus" />
    <DashLayout>
        <template #header>Data Gugus</template>
        <el-card>
            <template #header>
                <div class="flex items-center justify-between">
                    <h3>Manajemen Gugus</h3>
                    <div class="toolbar flex items-center">
                        <el-button type="primary" @click="addGugus" size="small">Tambah</el-button>
                    </div>
                </div>
            </template>
            <template #default>
                <el-table :data="props.guguses">
                    <el-table-column label="No" type="index"></el-table-column>
                    <el-table-column label="Nama" prop="nama"></el-table-column>
                    <el-table-column label="Anggota">
                        <template #default="{row}">
                            {{ row.sekolahs.length }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Detail" type="expand" width="100">
                        <template #default="{row}">
                            <div class="p-4 bg-sky-50">
                                <h3 class="font-bold text-sky-700">Data Anggota</h3>
                                <el-table :data="row.sekolahs">
                                    <el-table-column label="Nama">
                                        <template #default="scope">
                                            {{ scope.row.nama }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="Alamat">
                                        <template #default="scope">
                                            {{ scope.row.alamat }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="Guru">
                                        <template #default="scope">
                                            {{ scope.row.gurus?.length }}
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Sekretariat">
                        <template #default="{row}">
                            {{ row.sekretariat?.nama }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Ketua">
                        <template #default="{row}">
                            {{ row.sekretariat?.ks?.nama }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Opsi">
                        <template #default="{row}">
                            <el-button-group size="small">
                                <el-button @click="edit(row)">Edit</el-button>
                                <el-popconfirm title="Hapus Gugus?" @confirm="hapus(row.id)">
                                    <template #reference>
                                        <el-button type="danger">Hapus</el-button>
                                    </template>
                                </el-popconfirm>
                            </el-button-group>
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </el-card>
    </DashLayout>
    <el-dialog v-model="showForm" :show-close="false">
        <template #header>
            <div class="flex items-center justify-between">
                <h1>Formulir Gugus</h1>
                <el-button circle type="danger" @click="closeForm"><Icon icon="mdi-close" /></el-button>
            </div>
        </template>
        <template #default>
            <el-form v-model="gugus" label-width="200" v-loading="loading">
                <el-form-item label="Nama">
                    <el-input v-model="gugus.nama" placeholder="Nama Gugus"></el-input>
                </el-form-item>
                <el-form-item label="Deskripsi">
                    <el-input v-model="gugus.deskripsi" placeholder="Deskripsi Gugus"></el-input>
                </el-form-item>
                <el-form-item label="Sekretariat">
                    <el-select v-model="gugus.sekretariat" placeholder="Pilih Sekretariat" filterable>
                        <el-option v-for="(sekolah, s) in page.props.sekolahs" :value="sekolah.npsn" :label="sekolah.nama"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="">
                    <el-button type="primary" @click="simpan">Simpan</el-button>
                </el-form-item>
            </el-form>
        </template>
    </el-dialog>
</template>