<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import DashLayout from '@/Layouts/DashLayout.vue'

const cetakArsipRapor = (rombelId, siswaId, tapel, semester) => {
    let win = window.open(
        `/cetak/rapor/pas/${siswaId}?semester=${semester}&tapel=${tapel}&rombelId=${rombelId}`,
        "_blank",
        "popup=yes,width=1024,height=1500,scrollbars=no,toolbar=no,menubar=no",
    );
}
</script>

<template>
    <Head title="Arsip Rapor" />
    <DashLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Arsip Rapor
            </h2>
        </template>
        <el-card>
            <template #header>
                <h3 class="text-lg font-semibold">Daftar Rapor</h3>
            </template>
            <div class="mt-4">
                <el-collapse :accordion="true">
                    <el-collapse-item v-for="tapel in $page.props.tapels" :key="tapel.id" :name="tapel.id">
                        <template #title>
                            <div class="flex items-center">
                                <Icon icon="mdi:school" class="mr-2" />
                                {{ tapel.label }}
                            </div>
                        </template>
                        <div class="collapse-body">
                            <div class="content p-4">
                                <el-collapse accordion>
                                    <template v-for="rombel in tapel.rombels" :key="rombel.id">
                                        <el-collapse-item :name="rombel.id">
                                            <template #title>
                                                <div class="flex items-center">
                                                    <Icon icon="mdi:book" class="mr-2" />
                                                    {{ rombel.label }}
                                                </div>
                                            </template>
                                            <div class="collapse-body">
                                                <p>Wali Kelas: {{ rombel.wali_kelas.nama }}</p>
                                                <p>Jumlah Siswa: {{ rombel.siswas.length }}</p>
                                                <el-table :data="rombel.siswas" style="width: 100%">
                                                    <el-table-column prop="nisn" label="NISN" />
                                                    <el-table-column prop="nama" label="Nama Siswa" />
                                                    <el-table-column  label="Kelas">
                                                        <template #default="scope">
                                                            {{ rombel.tingkat }}
                                                        </template>
                                                    </el-table-column>
                                                    <el-table-column label="Semester">
                                                        <template #default="{row}">
                                                            <el-button-group size="small">
                                                                <el-button
                                                                    type="primary"
                                                                    @click="cetakArsipRapor(rombel.kode, row.nisn, tapel.kode, '1')"
                                                                >
                                                                    Ganjil
                                                                </el-button>
                                                                <el-button
                                                                    type="primary"
                                                                    @click="cetakArsipRapor(rombel.kode, row.nisn, tapel.kode, '2')"
                                                                >
                                                                    Genap
                                                                </el-button>
                                                            </el-button-group>
                                                        </template>
                                                    </el-table-column>
                                                </el-table>
                                            </div>
                                        </el-collapse-item>
                                    </template>
                                </el-collapse>
                            </div>
                        </div>
                    </el-collapse-item>
                </el-collapse>

            </div>
        </el-card>
    </DashLayout>
</template>
