<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import DashLayout from '@/Layouts/DashLayout.vue'
import FormEkskul from '@/Components/Dashboard/Periodik/FormEkskul.vue'
import FormAbsen from '@/Components/Dashboard/Periodik/FormAbsen.vue'
import FormCatatan from '@/Components/Dashboard/Periodik/FormCatatan.vue'


const page = usePage()

const rombel = page.props.rombels[0]
const selectedSiswa = ref({})
const mode = ref('list')

const openForm = (formulir, siswa) => {
    selectedSiswa.value = siswa
    mode.value = formulir
}

const closeForm = () => {
    selectedSiswa.value = false
    mode.value = 'list'
}
</script>

<template>
<Head title="Rapor Siswa" />

<DashLayout>
    <template #header>
        <h3>Rapor Siswa</h3>
    </template>
    <el-card>
        <template #header>
            <div>
                <h3 class="uppercase font-bold text-slate-600">Data Periodik Siswa {{ rombel.label }} {{ rombel.sekolah.nama }}</h3>
            </div>
        </template>
        <div class="card-body">
            <el-table :data="rombel.siswas" height="700" size="small">
                <el-table-column label="#" type="index" prop="scope.$index" />
                <el-table-column label="NISN" prop="nisn" />
                <el-table-column label="Nama" prop="nama" />
                <el-table-column label="JK" prop="jk" />
                <el-table-column label="Opsi">
                    <template #default="scope">
                        <div>
                            <el-button-group>
                                <el-button type="primary" @click="openForm('ekskul', scope.row)">Ekskul</el-button>
                                <el-button type="primary" @click="openForm('absen', scope.row)">Absensi</el-button>
                                <el-button type="primary" @click="openForm('catatan', scope.row)">Catatan</el-button>
                            </el-button-group>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </el-card>
</DashLayout>
<FormEkskul v-if="mode == 'ekskul'" :siswa="selectedSiswa" :rombel="rombel" :open="mode == 'ekskul'" @close="closeForm" />
<FormAbsen v-if="mode == 'absen'" :siswa="selectedSiswa" :rombel="rombel" :open="mode == 'absen'" @close="closeForm" />
<FormCatatan v-if="mode == 'catatan'" :siswa="selectedSiswa" :rombel="rombel" :open="mode == 'catatan'" @close="closeForm" />
</template>
