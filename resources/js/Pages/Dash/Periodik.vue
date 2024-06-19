<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import DashLayout from '@/Layouts/DashLayout.vue'

const page = usePage()

const rombel = page.props.rombels[0]
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
                                <el-button type="primary">Ekskul</el-button>
                                <el-button type="primary">Absensi</el-button>
                                <el-button type="primary">Fisik</el-button>
                            </el-button-group>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </el-card>
</DashLayout>

</template>
