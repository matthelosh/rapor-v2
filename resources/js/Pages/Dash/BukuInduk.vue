<script setup>
import { ref, computed } from "vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import DashLayout from "@/Layouts/DashLayout.vue";
import { fotoSiswa } from "@/helpers/Gambar";
import { capitalize } from "@/helpers/String";
import { ElMessage, ElMessageBox } from "element-plus";

const page = usePage();

const searchQuery = ref(page.props.filters?.search || "");
const statusFilter = ref(page.props.filters?.status || "all");

const statusOptions = [
    { label: "Semua Status", value: "all" },
    { label: "Aktif", value: "aktif" },
    { label: "Lulus", value: "lulus" },
    { label: "Pindah", value: "pindah" },
    { label: "Keluar", value: "keluar" },
];

const handleSearch = () => {
    router.get(
        route("dashboard.bukuinduk.home"),
        {
            search: searchQuery.value,
            status: statusFilter.value,
        },
        {
            preserveState: true,
        },
    );
};

const handleStatusFilter = (status) => {
    statusFilter.value = status;
    handleSearch();
};

const handleDelete = (id, nama) => {
    ElMessageBox.confirm(
        `Apakah Anda yakin ingin menghapus data buku induk ${nama}?`,
        "Konfirmasi Hapus",
        {
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal",
            type: "warning",
        },
    ).then(() => {
        router.delete(route("dashboard.bukuinduk.destroy", id), {
            onSuccess: () => {
                ElMessage.success("Data buku induk berhasil dihapus");
            },
        });
    });
};

const getStatusType = (status) => {
    const statusMap = {
        aktif: "success",
        lulus: "primary",
        pindah: "warning",
        keluar: "danger",
    };
    return statusMap[status] || "info";
};

const formatDate = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("id-ID");
};
</script>

<template>
    <DashLayout>
        <Head title="Buku Induk" />
        <template #header>BUKU INDUK SISWA</template>

        <div>
            <el-card>
                <!-- Header Controls -->
                <div
                    class="h-12 flex items-center justify-between bg-slate-100 shadow mb-4 rounded-lg px-4"
                >
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <el-input
                            v-model="searchQuery"
                            placeholder="Cari nama/NISN siswa..."
                            class="w-64"
                            @keyup.enter="handleSearch"
                            clearable
                        >
                            <template #prefix>
                                <Icon icon="material-symbols:search" />
                            </template>
                        </el-input>

                        <!-- Status Filter -->
                        <el-select
                            v-model="statusFilter"
                            placeholder="Filter Status"
                            @change="handleSearch"
                            class="w-40"
                        >
                            <el-option
                                v-for="option in statusOptions"
                                :key="option.value"
                                :label="option.label"
                                :value="option.value"
                            />
                        </el-select>

                        <el-button @click="handleSearch" type="primary">
                            <Icon icon="material-symbols:search" class="mr-1" />
                            Cari
                        </el-button>
                    </div>

                    <div class="flex items-center space-x-2">
                        <el-button
                            type="success"
                            @click="
                                router.visit(
                                    route('dashboard.bukuinduk.generate'),
                                )
                            "
                        >
                            <Icon
                                icon="material-symbols:auto-awesome"
                                class="mr-1"
                            />
                            Generate Auto
                        </el-button>

                        <!-- Add Button -->
                        <el-button
                            type="primary"
                            @click="
                                router.visit(
                                    route('dashboard.bukuinduk.create'),
                                )
                            "
                        >
                            <Icon icon="material-symbols:add" class="mr-1" />
                            Tambah Manual
                        </el-button>

                        <!-- Export Button -->
                        <el-button
                            type="info"
                            @click="
                                router.visit(
                                    route('dashboard.bukuinduk.export'),
                                    { method: 'post' },
                                )
                            "
                        >
                            <Icon
                                icon="material-symbols:download"
                                class="mr-1"
                            />
                            Export Excel
                        </el-button>

                        <!-- Print Button -->
                        <el-button
                            type="warning"
                            @click="
                                router.visit(route('dashboard.bukuinduk.print'))
                            "
                        >
                            <Icon icon="material-symbols:print" class="mr-1" />
                            Cetak
                        </el-button>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <el-card class="bg-green-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total Aktif</p>
                                <p class="text-2xl font-bold text-green-600">
                                    {{
                                        page.props.bukuInduks.data.filter(
                                            (b) => b.status === "aktif",
                                        ).length
                                    }}
                                </p>
                            </div>
                            <Icon
                                icon="material-symbols:person"
                                class="text-3xl text-green-600"
                            />
                        </div>
                    </el-card>

                    <el-card class="bg-blue-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Lulus</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{
                                        page.props.bukuInduks.data.filter(
                                            (b) => b.status === "lulus",
                                        ).length
                                    }}
                                </p>
                            </div>
                            <Icon
                                icon="material-symbols:school"
                                class="text-3xl text-blue-600"
                            />
                        </div>
                    </el-card>

                    <el-card class="bg-yellow-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Pindah</p>
                                <p class="text-2xl font-bold text-yellow-600">
                                    {{
                                        page.props.bukuInduks.data.filter(
                                            (b) => b.status === "pindah",
                                        ).length
                                    }}
                                </p>
                            </div>
                            <Icon
                                icon="material-symbols:move-location"
                                class="text-3xl text-yellow-600"
                            />
                        </div>
                    </el-card>

                    <el-card class="bg-red-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Keluar</p>
                                <p class="text-2xl font-bold text-red-600">
                                    {{
                                        page.props.bukuInduks.data.filter(
                                            (b) => b.status === "keluar",
                                        ).length
                                    }}
                                </p>
                            </div>
                            <Icon
                                icon="material-symbols:logout"
                                class="text-3xl text-red-600"
                            />
                        </div>
                    </el-card>
                </div>

                <!-- Data Table -->
                <el-table :data="page.props.bukuInduks.data" stripe>
                    <el-table-column
                        label="No"
                        type="index"
                        width="60"
                        fixed="left"
                    />

                    <el-table-column label="Foto" width="80" fixed="left">
                        <template #default="{ row }">
                            <el-avatar size="small">
                                <img :src="fotoSiswa(row.siswa)" />
                            </el-avatar>
                        </template>
                    </el-table-column>

                    <el-table-column label="No. Induk" width="100">
                        <template #default="{ row }">
                            {{ row.no_induk || "-" }}
                        </template>
                    </el-table-column>

                    <el-table-column
                        label="Nama Siswa"
                        width="200"
                        fixed="left"
                    >
                        <template #default="{ row }">
                            <div>
                                <p class="font-semibold">
                                    {{ row.siswa.nama.toUpperCase() }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ row.siswa.nisn }}
                                </p>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="JK" width="60">
                        <template #default="{ row }">
                            {{ row.siswa.jk === "Laki-laki" ? "L" : "P" }}
                        </template>
                    </el-table-column>

                    <el-table-column label="Tempat, Tgl Lahir" width="150">
                        <template #default="{ row }">
                            <div class="text-sm">
                                <p>{{ capitalize(row.siswa.tempat_lahir) }}</p>
                                <p class="text-gray-500">
                                    {{ formatDate(row.siswa.tanggal_lahir) }}
                                </p>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="Tanggal Masuk" width="120">
                        <template #default="{ row }">
                            {{ formatDate(row.tanggal_masuk) }}
                        </template>
                    </el-table-column>

                    <el-table-column label="Asal Sekolah" width="150">
                        <template #default="{ row }">
                            {{ row.asal_sekolah || "-" }}
                        </template>
                    </el-table-column>

                    <el-table-column label="Status" width="100">
                        <template #default="{ row }">
                            <el-tag :type="getStatusType(row.status)">
                                {{ row.status.toUpperCase() }}
                            </el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column label="Tanggal Keluar" width="120">
                        <template #default="{ row }">
                            {{ formatDate(row.tanggal_keluar) }}
                        </template>
                    </el-table-column>

                    <el-table-column label="Aksi" width="150" fixed="right">
                        <template #default="{ row }">
                            <div class="flex space-x-1">
                                <el-button
                                    size="small"
                                    @click="
                                        router.visit(
                                            route(
                                                'dashboard.bukuinduk.show',
                                                row.id,
                                            ),
                                        )
                                    "
                                >
                                    <Icon icon="material-symbols:visibility" />
                                </el-button>
                                <el-button
                                    size="small"
                                    type="warning"
                                    @click="
                                        router.visit(
                                            route(
                                                'dashboard.bukuinduk.edit',
                                                row.id,
                                            ),
                                        )
                                    "
                                >
                                    <Icon icon="material-symbols:edit" />
                                </el-button>
                                <el-button
                                    size="small"
                                    type="danger"
                                    @click="
                                        handleDelete(row.id, row.siswa.nama)
                                    "
                                >
                                    <Icon icon="material-symbols:delete" />
                                </el-button>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>

                <!-- Pagination -->
                <div class="mt-4 flex justify-center">
                    <el-pagination
                        v-if="page.props.bukuInduks.last_page > 1"
                        :current-page="page.props.bukuInduks.current_page"
                        :page-size="page.props.bukuInduks.per_page"
                        :total="page.props.bukuInduks.total"
                        layout="prev, pager, next, total"
                        @current-change="
                            (page) =>
                                router.visit(
                                    route('dashboard.bukuinduk.home', { page }),
                                )
                        "
                    />
                </div>
            </el-card>
        </div>
    </DashLayout>
</template>
