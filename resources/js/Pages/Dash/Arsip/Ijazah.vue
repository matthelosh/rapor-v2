<script setup>
import { ref, defineAsyncComponent } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { Search } from '@element-plus/icons-vue'
import axios from 'axios';
const page = usePage()
const DashLayout = defineAsyncComponent(() => import('@/Layouts/DashLayout.vue'))

const loading = ref(false)
const arsip = ref({})
const siswa = ref(null)
const formArsip = ref(false)
const tambahArsip = (tapel) => {
    arsip.value = {tapel_id: tapel.id}
    formArsip.value = true
}

const cariSiswa = async() => {
    loading.value = true
    siswa.value = null
    axios.post(route('dashboard.siswa.cari', {nisn: arsip.value.siswa_id}))
        .then(res => {
            if (res.data.siswa !== null) {
                siswa.value = res.data.siswa
            } else {
                ElMessageBox.alert('Siswa tidak ditemukan', 'Info', {
                    confirmButtonText: 'OK',
                    type: 'info'
                })
            }
        }).catch(err => {
            console.log(err)
        }).finally(() => {
            loading.value = false
        })
}
const editArsip = (item) => {
    arsip.value = item
    formArsip.value = true
}

const hapusArsip = (id) => {
    router.delete(route('dashboard.ijazah.destroy', {id: id}), {

    })
        

}
</script>

<template>
    <Head title="Arsip Ijazah" />
     <DashLayout title="Arsip Ijazah">
        <template #header>
            <p class="uppercase">Arsip Ijazah</p>
        </template>
        <el-card>
            <template #header>
                <div class="flex justify-between">
                    <h3 class="font-bold text-sky-800">Pilih Tahun Ijazah</h3>
                </div>
            </template>
            <el-collapse accordion>
                <template v-for="(tapel, t) in page.props.tapels" :key="t">
                    <el-collapse-item>
                        <template #title>
                            <div class="p-2">
                                {{ tapel.label }}
                            </div>
                        </template>
                        <div class="collapse-body p-4 bg-slate-100">
                            <div class="toolbar">
                                <el-button size="small" @click="tambahArsip(tapel)">Tambah</el-button>
                            </div>
                            <div class="content py-4">
                                <el-table :data="tapel.ijazahs" max-height="400">
                                    <el-table-column label="#" type="index" />
                                    <el-table-column label="No. Seri" prop="no_seri"></el-table-column>
                                    <el-table-column label="Siswa">
                                        <template #default="{row}">
                                            {{ row.siswa.nama }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="Keterangan" prop="keterangan"></el-table-column>
                                    <el-table-column label="Opsi" width="250" fixed="right">
                                        <template #default="{row}">
                                            <div class="flex gap-2">
                                                <a :href="row.url" target="_blank" class="text-sky-500 flex gap-1 items-center">
                                                    <Icon icon="mdi-magnify" />
                                                    Lihat
                                                </a>
                                                <el-button-group>
                                                <el-button :native-type="null" text type="warning" @click="editArsip(row)">
                                                    <Icon icon="mdi-pencil" />
                                                    Edit
                                                </el-button>
                                                <el-button :native-type="null" text type="danger" @click="hapusArsip(row.id)">
                                                    <Icon icon="mdi-trash" />
                                                    Hapus
                                                </el-button>
                                                </el-button-group>
                                            </div>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </div>
                    </el-collapse-item>
                </template>
            </el-collapse>
        </el-card>
        
        <el-dialog v-model="formArsip" @close="formArsip = false" :loading="loading">
            <template #header>
                <span class="flex items-center justify-between">
                    <h3>Tambah Arsip Ijazah <b>{{ siswa?.nama }}</b></h3>
                </span>
            </template>    
            <template #default>
                <el-form v-model="arsip" label-position="top" :disabled="loading">
                    <el-row :gutter="10">
                        <el-col :span="8">
                            <el-form-item label="Masukkan NISN">
                                <el-input placeholder="NISN Siswa" v-model="arsip.siswa_id">
                                    <template #append>
                                        <el-button :icon="Search" @click="cariSiswa" />
                                    </template>
                                    </el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-form>
            </template>
        </el-dialog>

            
    </DashLayout>
</template>