<script setup>
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
);

// Props: array siswa
const props = defineProps({
    siswa: Object,
});

const labels = [
    "Bangun Pagi",
    "Beribadah",
    "Berolahraga",
    "Makan Sehat dan Bergizi",
    "Gemar Belajar",
    "Bermasyarakat",
    "Tidur Cepat",
];

// Menyusun data untuk setiap siswa
const datasets = {
    label: siswa.nama,
    data: [
        siswa.kebiasaan_count.bangun_pagi || 0,
        siswa.kebiasaan_count.beribadah || 0,
        siswa.kebiasaan_count.berolahraga || 0,
        siswa.kebiasaan_count.makan_sehat || 0,
        siswa.kebiasaan_count.gemar_belajar || 0,
        siswa.kebiasaan_count.bermasyarakat || 0,
        siswa.kebiasaan_count.tidur_awal || 0,
    ],
    backgroundColor: colors[index % colors.length],
};

const data = {
    labels: labels,
    datasets: datasets,
};

const options = {
    responsive: true,
    plugins: {
        legend: {
            position: "top",
        },
        title: {
            display: true,
            text: "Perbandingan Kebiasaan Siswa per Bulan",
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            max: 30,
        },
    },
};
</script>

<template>
    <Bar :data="data" :options="options" />
</template>
