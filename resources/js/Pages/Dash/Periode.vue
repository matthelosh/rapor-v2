<script setup>
import { ref, computed } from "vue";
import { router, Head } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import DashLayout from "@/Layouts/DashLayout.vue";
defineProps({
    tapels: Array,
    semesters: Array,
});
</script>

<template>
    <Head title="Periode Akademik" />
    <DashLayout title="Periode Akademik">
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-2">
            <el-card>
                <template #header>
                    <div class="flex items-center justify-between">
                        <h3>Tahun Pelajaran</h3>
                        <el-button type="primary" size="small"
                            >Tambah</el-button
                        >
                    </div>
                </template>
                <template #default>
                    <el-table :data="tapels">
                        <el-table-column label="#" type="index">
                        </el-table-column>
                        <el-table-column label="Kode" prop="kode" />
                        <el-table-column label="Label" prop="label" />
                        <el-table-column label="Status" prop="is_active" />
                        <el-table-column label="Deskripsi" prop="deskripsi" />
                        <el-table-column label="Opsi">
                            <el-button type="warning" circle size="small">
                                <Icon icon="mdi:edit" />
                            </el-button>
                            <el-button type="danger" circle size="small">
                                <Icon icon="mdi:trash" />
                            </el-button>
                        </el-table-column>
                    </el-table>
                </template>
            </el-card>
            <el-card>
                <template #header>
                    <div class="flex items-center justify-between">
                        <h3>Data Semester</h3>
                    </div>
                </template>
                <template #default>
                    <el-table :data="semesters">
                        <el-table-column label="Kode" prop="kode" />
                        <el-table-column label="Label" prop="label" />
                        <el-table-column label="Deskripsi" prop="deskripsi" />
                        <el-table-column label="Status" prop="is_active" />
                    </el-table>
                </template>
            </el-card>
        </div>
    </DashLayout>
</template>
