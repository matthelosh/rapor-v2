<script setup>
import { computed } from "vue";
import { Radar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
} from "chart.js";
import dayjs from "dayjs";
import "dayjs/locale/id";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
);

const props = defineProps({ siswa: Object, bulanTahun: String });

const labels = [
    "Bangun Pagi",
    "Beribadah",
    "Berolahraga",
    "Makan Sehat dan Bergizi",
    "Gemar Belajar",
    "Bermasyarakat",
    "Tidur Cepat",
];

const data = {
    labels: labels,
    // data: [4, 5, 3, 0, 4, 5, 3],
    datasets: [
        {
            // label: props.siswa.nama,
            label: "Terlaksana",
            // data: labels.map(
            //     (label) => props.siswa.kebiasaan_count[label] || 0,
            // ),
            data: [10, 3, 40, 20, 15, 25, 30],
            backgroundColor: [
                "rgba(200, 199, 232, 0.5)",
                "rgba(54, 162, 235, 1)",
                "rgba(255, 206, 86, 1)",
                "rgba(75, 192, 192, 1)",
                "rgba(153, 102, 255, 1)",
                "rgba(255, 159, 64, 1)",
                "rgba(255, 99, 132, 1)",
            ],
            borderColor: [
                "rgba(250, 99, 132, 0.7)",
                "rgba(54, 162, 235, 1)",
                "rgba(255, 206, 86, 1)",
                "rgba(75, 192, 192, 1)",
                "rgba(153, 102, 255, 1)",
                "rgba(255, 159, 64, 1)",
                "rgba(255, 99, 132, 1)",
            ],
            borderWidth: 2,
        },
    ],
};

const bulan = computed(() =>
    dayjs(props.bulanTahun + "-01")
        .locale("id")
        .format("MMMM YYYY"),
);
</script>

<template>
    <!-- {{ labels.map((label) => siswa.kebiasaan_count[label] || 0) }} -->
    <h3 class="text-center uppercase font-bold">
        Peta Kekuatan 7 KAIH {{ siswa.nama }}
    </h3>
    <h3 class="text-center text-xl font-['Arial'] font-black">{{ bulan }}</h3>
    <div
        class="chart-container w-[600px] h-[600px] mx-auto mt-4 rounded hover:shadow-lg p-4"
    >
        <Radar :data="data" :options="{ responsive: true }" />
    </div>
</template>
