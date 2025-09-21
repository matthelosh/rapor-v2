<script setup>
import { computed } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import DashLayout from "@/Layouts/DashLayout.vue";
import { fotoSiswa } from "@/helpers/Gambar";
import { capitalize } from "@/helpers/String";

const props = defineProps({
    bukuInduk: Object,
});

const formatDate = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("id-ID", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const formatDateShort = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleDateString("id-ID");
};

const getStatusColor = (status) => {
    const statusMap = {
        aktif: "success",
        lulus: "primary",
        pindah: "warning",
        keluar: "danger",
    };
    return statusMap[status] || "info";
};

const handleBack = () => {
    router.visit(route("dashboard.bukuinduk.home"));
};

const handleEdit = () => {
    router.visit(route("dashboard.bukuinduk.edit", props.bukuInduk.id));
};

const handlePrint = () => {
    window.print();
};

const siswa = computed(() => props.bukuInduk.siswa);
</script>

<template>
    <DashLayout>
        <Head title="Detail Buku Induk" />
        <template #header>DETAIL BUKU INDUK SISWA</template>

        <div class="space-y-6">
            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <el-button @click="handleBack" type="info">
                    <Icon icon="material-symbols:arrow-back" class="mr-1" />
                    Kembali
                </el-button>

                <div class="space-x-2">
                    <el-button @click="handlePrint" type="success">
                        <Icon icon="material-symbols:print" class="mr-1" />
                        Cetak
                    </el-button>
                    <el-button @click="handleEdit" type="warning">
                        <Icon icon="material-symbols:edit" class="mr-1" />
                        Edit Data
                    </el-button>
                </div>
            </div>

            <!-- Main Card -->
            <el-card class="print-content bg-white">
                <!-- Header Info -->
                <div class="text-center mb-8 print-header">
                    <h1 class="text-2xl font-bold mb-2">BUKU INDUK SISWA</h1>
                    <p class="text-lg text-gray-600">
                        {{ siswa.sekolah?.nama || "SD NEGERI" }}
                    </p>
                    <div class="w-24 h-1 bg-blue-500 mx-auto mt-4"></div>
                </div>

                <!-- Student Photo and Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="md:col-span-1 text-center">
                        <div
                            class="inline-block p-2 border-2 border-gray-300 rounded-lg"
                        >
                            <el-avatar :size="150" shape="square">
                                <img
                                    :src="fotoSiswa(siswa)"
                                    class="w-full h-full object-cover"
                                />
                            </el-avatar>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">Foto Siswa</p>

                        <div class="mt-4">
                            <el-tag
                                :type="getStatusColor(bukuInduk.status)"
                                size="large"
                                class="font-bold"
                            >
                                STATUS: {{ bukuInduk.status.toUpperCase() }}
                            </el-tag>
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Data Pribadi -->
                            <div>
                                <h3
                                    class="text-lg font-semibold mb-4 text-blue-700 border-b-2 border-blue-200 pb-2"
                                >
                                    DATA PRIBADI SISWA
                                </h3>

                                <div class="space-y-3">
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >Nama Lengkap</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{ siswa.nama.toUpperCase() }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >NISN</span
                                        >
                                        <span class="font-semibold"
                                            >: {{ siswa.nisn }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >NIS</span
                                        >
                                        <span class="font-semibold"
                                            >: {{ siswa.nis || "-" }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >NIK</span
                                        >
                                        <span class="font-semibold"
                                            >: {{ siswa.nik || "-" }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >Jenis Kelamin</span
                                        >
                                        <span class="font-semibold"
                                            >: {{ siswa.jk }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >Tempat Lahir</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{
                                                capitalize(siswa.tempat_lahir)
                                            }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >Tanggal Lahir</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{
                                                formatDateShort(
                                                    siswa.tanggal_lahir,
                                                )
                                            }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >Agama</span
                                        >
                                        <span class="font-semibold"
                                            >: {{ siswa.agama }}</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Data Buku Induk -->
                            <div>
                                <h3
                                    class="text-lg font-semibold mb-4 text-green-700 border-b-2 border-green-200 pb-2"
                                >
                                    DATA BUKU INDUK
                                </h3>

                                <div class="space-y-3">
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >No. Induk</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{
                                                bukuInduk.no_induk || "-"
                                            }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >Tanggal Masuk</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{
                                                formatDateShort(
                                                    bukuInduk.tanggal_masuk,
                                                )
                                            }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >Asal Sekolah</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{
                                                bukuInduk.asal_sekolah || "-"
                                            }}</span
                                        >
                                    </div>
                                    <div class="flex">
                                        <span class="w-32 text-gray-600"
                                            >Status</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{
                                                bukuInduk.status.toUpperCase()
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="flex"
                                        v-if="bukuInduk.tanggal_keluar"
                                    >
                                        <span class="w-32 text-gray-600"
                                            >Tanggal Keluar</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{
                                                formatDateShort(
                                                    bukuInduk.tanggal_keluar,
                                                )
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        class="flex"
                                        v-if="bukuInduk.alasan_keluar"
                                    >
                                        <span class="w-32 text-gray-600"
                                            >Alasan Keluar</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{ bukuInduk.alasan_keluar }}</span
                                        >
                                    </div>
                                    <div
                                        class="flex"
                                        v-if="bukuInduk.sekolah_tujuan"
                                    >
                                        <span class="w-32 text-gray-600"
                                            >Sekolah Tujuan</span
                                        >
                                        <span class="font-semibold"
                                            >:
                                            {{ bukuInduk.sekolah_tujuan }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-8">
                    <h3
                        class="text-lg font-semibold mb-4 text-purple-700 border-b-2 border-purple-200 pb-2"
                    >
                        ALAMAT LENGKAP
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="font-semibold">
                            {{ capitalize(siswa.alamat) }}
                            <span v-if="siswa.rt || siswa.rw">
                                , RT. {{ siswa.rt || "-" }} RW
                                {{ siswa.rw || "-" }}
                            </span>
                            <br />
                            Desa {{ capitalize(siswa.desa) }}, Kec.
                            {{ capitalize(siswa.kecamatan) }}
                            <br />
                            {{ siswa.kabupaten }}, {{ siswa.provinsi }}
                            <span v-if="siswa.kode_pos">
                                - {{ siswa.kode_pos }}</span
                            >
                        </p>
                        <div class="mt-2 text-sm text-gray-600">
                            <Icon icon="material-symbols:phone" class="mr-1" />
                            Telp/HP: {{ siswa.hp || "-" }}
                            <span v-if="siswa.email" class="ml-4">
                                <Icon
                                    icon="material-symbols:email"
                                    class="mr-1"
                                />
                                Email: {{ siswa.email }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Data Orang Tua -->
                <div class="mb-8" v-if="siswa.ortus && siswa.ortus.length > 0">
                    <h3
                        class="text-lg font-semibold mb-4 text-orange-700 border-b-2 border-orange-200 pb-2"
                    >
                        DATA ORANG TUA / WALI
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            v-for="ortu in siswa.ortus"
                            :key="ortu.id"
                            class="bg-gray-50 p-4 rounded-lg"
                        >
                            <h4 class="font-semibold text-gray-700 mb-2">
                                {{ ortu.status?.toUpperCase() }}
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex">
                                    <span class="w-24 text-gray-600">Nama</span>
                                    <span>: {{ ortu.nama }}</span>
                                </div>
                                <div class="flex">
                                    <span class="w-24 text-gray-600"
                                        >Pekerjaan</span
                                    >
                                    <span>: {{ ortu.pekerjaan || "-" }}</span>
                                </div>
                                <div class="flex">
                                    <span class="w-24 text-gray-600"
                                        >Pendidikan</span
                                    >
                                    <span>: {{ ortu.pendidikan || "-" }}</span>
                                </div>
                                <div class="flex">
                                    <span class="w-24 text-gray-600"
                                        >Telepon</span
                                    >
                                    <span>: {{ ortu.hp || "-" }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-8" v-if="bukuInduk.keterangan">
                    <h3
                        class="text-lg font-semibold mb-4 text-red-700 border-b-2 border-red-200 pb-2"
                    >
                        KETERANGAN
                    </h3>
                    <div
                        class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400"
                    >
                        <p class="text-gray-800">{{ bukuInduk.keterangan }}</p>
                    </div>
                </div>

                <!-- Footer untuk print -->
                <div class="print-footer mt-12">
                    <div class="text-center text-sm text-gray-600">
                        <p>Dicetak pada: {{ formatDate(new Date()) }}</p>
                        <div class="mt-8 grid grid-cols-2 gap-8">
                            <div>
                                <p>Kepala Sekolah</p>
                                <div class="h-16"></div>
                                <p class="border-t border-gray-400 pt-2">
                                    (...........................)
                                </p>
                            </div>
                            <div>
                                <p>Operator</p>
                                <div class="h-16"></div>
                                <p class="border-t border-gray-400 pt-2">
                                    (...........................)
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </el-card>
        </div>
    </DashLayout>
</template>

<style>
@media print {
    .el-card {
        box-shadow: none !important;
        border: none !important;
    }

    .print-content {
        margin: 0;
        padding: 20px;
    }

    .print-header {
        margin-bottom: 30px;
    }

    .print-footer {
        margin-top: 50px;
        page-break-inside: avoid;
    }

    /* Hide non-print elements */
    nav,
    .el-button,
    .actions {
        display: none !important;
    }
}
</style>
