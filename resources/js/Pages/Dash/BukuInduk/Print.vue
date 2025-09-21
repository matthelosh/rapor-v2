<script setup>
import { onMounted } from "vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    bukuInduks: Array,
    sekolah: Object,
    filters: Object
});

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('id-ID');
};

const getStatusBadge = (status) => {
    const colors = {
        'aktif': '#10B981',
        'lulus': '#3B82F6',
        'pindah': '#F59E0B',
        'keluar': '#EF4444'
    };
    return colors[status] || '#6B7280';
};

onMounted(() => {
    // Auto print when page loads
    window.print();
});
</script>

<template>
    <div class="print-page">
        <Head title="Cetak Buku Induk" />

        <div class="print-content">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2">BUKU INDUK SISWA</h1>
                <h2 class="text-xl mb-2">{{ sekolah.nama }}</h2>
                <p class="text-sm text-gray-600 mb-1">{{ sekolah.alamat }}</p>
                <p class="text-sm text-gray-600 mb-4">Telp: {{ sekolah.telepon }} - Email: {{ sekolah.email }}</p>
                <div class="border-b-2 border-gray-800 w-full mx-auto"></div>
            </div>

            <!-- Filter Info -->
            <div class="mb-6">
                <p class="text-sm">
                    <strong>Filter:</strong>
                    Status {{ filters.status === 'all' ? 'Semua' : filters.status?.toUpperCase() }}
                    | Dicetak pada: {{ formatDate(new Date()) }}
                    | Total: {{ bukuInduks.length }} siswa
                </p>
            </div>

            <!-- Table -->
            <table class="w-full border-collapse border border-gray-400 text-sm">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-400 p-2 text-left">No</th>
                        <th class="border border-gray-400 p-2 text-left">No. Induk</th>
                        <th class="border border-gray-400 p-2 text-left">Nama Siswa</th>
                        <th class="border border-gray-400 p-2 text-left">NISN</th>
                        <th class="border border-gray-400 p-2 text-left">JK</th>
                        <th class="border border-gray-400 p-2 text-left">Tempat, Tgl Lahir</th>
                        <th class="border border-gray-400 p-2 text-left">Tgl Masuk</th>
                        <th class="border border-gray-400 p-2 text-left">Asal Sekolah</th>
                        <th class="border border-gray-400 p-2 text-left">Status</th>
                        <th class="border border-gray-400 p-2 text-left">Tgl Keluar</th>
                        <th class="border border-gray-400 p-2 text-left">Alasan Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(bukuInduk, index) in bukuInduks" :key="bukuInduk.id">
                        <td class="border border-gray-400 p-2">{{ index + 1 }}</td>
                        <td class="border border-gray-400 p-2">{{ bukuInduk.no_induk || '-' }}</td>
                        <td class="border border-gray-400 p-2">{{ bukuInduk.siswa.nama.toUpperCase() }}</td>
                        <td class="border border-gray-400 p-2">{{ bukuInduk.siswa.nisn }}</td>
                        <td class="border border-gray-400 p-2">{{ bukuInduk.siswa.jk === 'Laki-laki' ? 'L' : 'P' }}</td>
                        <td class="border border-gray-400 p-2">
                            {{ bukuInduk.siswa.tempat_lahir }}, {{ formatDate(bukuInduk.siswa.tanggal_lahir) }}
                        </td>
                        <td class="border border-gray-400 p-2">{{ formatDate(bukuInduk.tanggal_masuk) }}</td>
                        <td class="border border-gray-400 p-2">{{ bukuInduk.asal_sekolah || '-' }}</td>
                        <td class="border border-gray-400 p-2">
                            <span
                                class="inline-block px-2 py-1 text-xs text-white rounded"
                                :style="{ backgroundColor: getStatusBadge(bukuInduk.status) }"
                            >
                                {{ bukuInduk.status.toUpperCase() }}
                            </span>
                        </td>
                        <td class="border border-gray-400 p-2">{{ formatDate(bukuInduk.tanggal_keluar) }}</td>
                        <td class="border border-gray-400 p-2">{{ bukuInduk.alasan_keluar || '-' }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Footer -->
            <div class="mt-8 grid grid-cols-3 gap-8 text-center">
                <div>
                    <p class="mb-16">Mengetahui,</p>
                    <p class="mb-2">Kepala Sekolah</p>
                    <p class="border-t border-gray-400 pt-2">(...........................)</p>
                </div>
                <div></div>
                <div>
                    <p class="mb-2">{{ formatDate(new Date()) }}</p>
                    <p class="mb-14">Operator</p>
                    <p class="border-t border-gray-400 pt-2">(...........................)</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.print-page {
    background: white;
    padding: 20px;
    font-family: 'Arial', sans-serif;
}

.print-content {
    max-width: 100%;
}

@media print {
    body * {
        visibility: hidden;
    }

    .print-page,
    .print-page * {
        visibility: visible;
    }

    .print-page {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 0;
        margin: 0;
    }

    table {
        page-break-inside: auto;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    thead {
        display: table-header-group;
    }

    @page {
        margin: 1cm;
        size: A4 landscape;
    }
}
</style>
