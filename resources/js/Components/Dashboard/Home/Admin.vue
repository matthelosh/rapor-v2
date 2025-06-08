<script setup>
import { ref, computed, onMounted } from "vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { dataAgama, dataJk } from "@/helpers/data";
import { useTransition } from "@vueuse/core";

import Tiptap from "@/Components/Tiptap.vue";

import { Doughnut } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
    LinearScale,
);

const page = usePage();

const sekolahs = computed(() => page.props.data.sekolahs);

const labels = computed(() => sekolahs.value.map((sekolah) => sekolah.nama));
const selectedSekolah = ref("all");
const dataApa = ref("siswas");

const datas = computed(() => {
    if (selectedSekolah.value == "all") {
        return sekolahs.value.reduce((a, c) => a.concat(c[dataApa.value]), []);
    } else {
        return sekolahs.value[selectedSekolah.value][dataApa.value];
    }
});

const byAgama = computed(() => {
    return {
        labels: ["Islam", "Kristen", "Katolik", "Hindu", "Budha", "Konghuchu"],
        datasets: [
            {
                // label: ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghuchu'],
                data: dataAgama(datas.value),
                backgroundColor: [
                    "#177E89",
                    "#084C61",
                    "#DB3A34",
                    "#FFC857",
                    "#997C44",
                    "#323031",
                ],
            },
        ],
    };
});

const byJk = computed(() => {
    return {
        labels: ["Laki-laki", "Perempuan"],
        datasets: [
            {
                // label: ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghuchu'],
                data: dataJk(datas.value),
                backgroundColor: ["#177E89", "#fe8999"],
            },
        ],
    };
});

const content = ref('<strong>Halo</strong>')

const jml = (total) => {
    return useTransition(total, { duration: 1500 });
};
// const jmlSiswa = computed(() => sekolahs.value[selectedSekolah.value].siswas.length)

const tes = () => {
    router.post(route('dashboard.home.tes'))
}
onMounted(() => {
    // console.log(import.meta.env.VITE_REVERB_HOST)
    window.Echo.channel('tes')
        .listen('JawabanReceived', (e) => {
            console.log(e)
        })

})
</script>

<template>
    <el-row class="w-full" :gutter="20">
        <el-col :span="16" :xs="24">
            <el-card>
                <template #header>
                    <h3>Data Agama Siswa</h3>
                </template>
                <div class="card-body">
                    <el-row :gutter="20">
                        <el-col :span="12" :xs="24">
                            <Doughnut
                                ref="chartAgama"
                                :options="{ responsive: true }"
                                :data="byAgama"
                            />
                        </el-col>
                        <el-col :span="12" :xs="24">
                            <el-card shadow="never">
                                <h3 class="font-bold">Keterangan:</h3>
                                <table class="w-full">
                                    <tbody>
                                    <tr
                                        v-for="(ag, a) in [
                                            'Islam',
                                            'Kristen',
                                            'Katolik',
                                            'Hindu',
                                            'Budha',
                                            'Konghuchu',
                                        ]"
                                        :key="a"
                                    >
                                        <td class="border-y">{{ ag }}</td>
                                        <td class="border-y">:</td>
                                        <td class="border-y">
                                            <el-statistic
                                                :value="
                                                    jml(dataAgama(datas)[a])
                                                "
></el-statistic>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah</td>
                                        <td>:</td>
                                        <td>{{datas.length}}</td>

                                    </tr>
                                    </tbody>
                                </table>
                            </el-card>
                        </el-col>
                    </el-row>
                </div>
            </el-card>
        </el-col>
        <el-col :span="8" :xs="24">
            <el-card style="width: 100%">
                <template #header>
                    <h3>Pilih Sekolah</h3>
                    <div class="toolbar flex justify-between w-full mb-6">
                        <el-select v-model="selectedSekolah">
                            <el-option
                                value="all"
                                label="Semua Sekolah"
                            ></el-option>
                            <el-option
                                v-for="(sekolah, s) in sekolahs"
                                :key="s"
                                :value="s"
                                :label="sekolah.nama"
                            ></el-option>
                        </el-select>
                    </div>
                </template>
                <template #default>
                </template>
            </el-card>
        </el-col>
    </el-row>
    <el-row style="margin-top: 20px;">
        <el-col>
            <!-- <Tiptap v-model="content" /> -->
             <el-button @click="tes">Tes Reverb</el-button>
        </el-col>
    </el-row>
</template>
