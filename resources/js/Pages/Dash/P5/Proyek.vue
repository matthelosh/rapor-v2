<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'

import DashLayout from '@/Layouts/DashLayout.vue';
const mode = ref('form')
const page = usePage()
const props = defineProps({ proyeks: Array})
const role = computed(() => {
    return page.props.auth.user.roles[0]
})

const proyek = ref({})
</script>

<template>
<Head title="Data Proyek" />

<DashLayout>
    <template #header>
        Data Proyek
    </template>

    <el-card >
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Manajemen Proyek P5</h3>
                <div class="flex items-center">
                    <el-button type="primary">Buat Baru</el-button>
                </div>
            </div>
        </template>
        <template #default>
            <el-table :data="props.proyeks" v-if="mode == 'list'">
                <el-table-column label="Tapel" prop="tapel"></el-table-column>
                <el-table-column label="Semester" prop="semester"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <el-table-column label="Rombel">
                    <template #default="scope">
                        {{ scope.row.rombel.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Deksripsi" prop="deskripsi"></el-table-column>
                <el-table-column label="Status" prop="status"></el-table-column>
                <el-table-column label="Opsi" v-if="role == 'guru_kelas'">
                    <template #default="scope">
                        {{ scope.row.rombel.label }}
                    </template>
                </el-table-column>
            </el-table>
            <div class="form " v-if="mode == 'form'">
                <h3 class="text-lg font-bold text-sky-700 text-center mb-4 uppercase">Formulir Proyek P5</h3>
                <el-form v-model="proyek" label-width="200">
                    <el-form-item label="Nama">
                        <el-input v-model="proyek.nama" placeholder="Nama Proyek"></el-input>
                    </el-form-item>
                    <el-form-item label="Deskripsi">
                        <el-input type="textarea" v-model="proyek.deskripsi" placeholder="Deskripsi Proyek"></el-input>
                    </el-form-item>
                </el-form>
            </div>
        </template>
    </el-card>
</DashLayout>
</template>