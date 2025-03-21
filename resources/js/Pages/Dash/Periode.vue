<script setup>
import { ref, computed } from "vue";
import { router, Head } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import DashLayout from "@/Layouts/DashLayout.vue";
defineProps({
    tapels: Array,
    semesters: Array,
});
const loading = ref(false);

const toggleTapel = async (item) => {
    router.put(route("dashboard.setting.tapel.toggle", { id: item.id }), null, {
        onStart: () => (loading.value = true),
        onSuccess: () => {
            router.reload({ only: ["tapels"] });
            ElNotification({
                title: "Info",
                message: page.props.flash.message,
                type: "success",
            });
        },
        onFinish: () => (loading.value = false),
    });
};
const toggleSemester = async (item) => {
    router.put(
        route("dashboard.setting.semester.toggle", { id: item.id }),
        null,
        {
            onStart: () => (loading.value = true),
            onSuccess: () => {
                router.reload({ only: ["semesters"] });
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: "success",
                });
            },
            onFinish: () => (loading.value = false),
        }
    );
};
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
                        <el-table-column label="Status">
                            <template #default="{ row }">
                                <el-button
                                    :type="row.is_active ? 'success' : ''"
                                    circle
                                    size="small"
                                    @click="toggleTapel(row)"
                                >
                                    <Icon icon="mdi:check" />
                                </el-button>
                            </template>
                        </el-table-column>
                        <el-table-column label="Deskripsi" prop="deskripsi" />
                        <el-table-column label="Opsi">
                            <template #default="{ row }">
                                <el-button type="warning" circle size="small">
                                    <Icon icon="mdi:edit" />
                                </el-button>
                                <el-button type="danger" circle size="small">
                                    <Icon icon="mdi:trash" />
                                </el-button>
                            </template>
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
                        <el-table-column label="Status">
                            <template #default="{ row }">
                                <el-button
                                    :type="row.is_active ? 'success' : ''"
                                    circle
                                    size="small"
                                    @click="toggleSemester(row)"
                                >
                                    <Icon icon="mdi:check" />
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </template>
            </el-card>
        </div>
    </DashLayout>
</template>
