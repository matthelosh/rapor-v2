<script setup>
import { Line } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    LinearScale,
    CategoryScale,
);

import dayjs from "dayjs";
import isoWeek from "dayjs/plugin/isoWeek";
dayjs.extend(isoWeek);

const props = defineProps({
    siswa: Object,
    rombel: Object,
    bulan: String,
});

const kebiasaanLabels = [
    "Bangun Pagi",
    "Beribadah",
    "Berolahraga",
    "Makan Sehat dan Bergizi",
    "Gemar Belajar",
    "Bermasyarakat",
    "Tidur Cepat",
];

// Buat map mingguan dari kaihs
const weeklyMap = {};
for (const item of props.siswa.kaihs) {
    const d = dayjs(item.waktu);
    const weekKey = `${d.year()}-W${String(d.isoWeek()).padStart(2, "0")}`;
    if (!weeklyMap[weekKey]) {
        weeklyMap[weekKey] = {};
    }
    const kebiasaan = item.kebiasaan;
    if (!weeklyMap[weekKey][kebiasaan]) {
        weeklyMap[weekKey][kebiasaan] = 0;
    }
    if (item.is_done) {
        weeklyMap[weekKey][kebiasaan] += 1;
    }
}

// Urutkan week key
const weekLabels = Object.keys(weeklyMap).sort();

// Buat dataset per kebiasaan
const warnaDasar = [
    "rgba(255, 99, 132)",
    "rgba(54, 162, 235)",
    "rgba(255, 206, 86)",
    "rgba(75, 192, 192)",
    "rgba(153, 102, 255)",
    "rgba(255, 159, 64)",
    "rgba(100, 200, 50)",
];

const datasets = kebiasaanLabels.map((label, i) => ({
    label: label,
    data: weekLabels.map((week) => weeklyMap[week][label] || 0),
    borderColor: warnaDasar[i],
    backgroundColor: warnaDasar[i],
    // borderColor: warnaDasar[i] + ", 1)",
    // backgroundColor: warnaDasar[i] + ", 0.2)",
    tension: 0,
}));

const chartData = {
    labels: weekLabels,
    datasets,
};

const options = {
    responsive: true,
    plugins: {
        title: {
            display: true,
            text: `Perkembangan Kebiasaan per Minggu ${props.siswa.nama}`,
        },
    },
    scales: {
        y: {
            beginAtZero: true,
        },
    },
};
</script>

<template>
    <Line :data="chartData" :options="options" />
</template>
