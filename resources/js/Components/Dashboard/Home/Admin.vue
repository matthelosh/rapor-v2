<script setup>
import { ref, computed } from 'vue'
import { usePage, Head, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { dataAgama, dataJk } from '@/helpers/data';
import { useTransition } from '@vueuse/core'

import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement,CategoryScale, LinearScale)

const page = usePage()

const sekolahs = computed(() => page.props.data.sekolahs)

const labels = computed(() => sekolahs.value.map(sekolah => sekolah.nama))
const selectedSekolah = ref('all')
const dataApa = ref('siswas')

const datas = computed(() => {
    if (selectedSekolah.value == 'all') {
        return sekolahs.value.reduce((a,c,cI,ar) => (a.concat(c[dataApa.value])), [])
    } else {
        return sekolahs.value[selectedSekolah.value][dataApa.value]
    }

})

const byAgama = computed(() => {
    return {
        labels: ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghuchu'],
        datasets: [
            {
                // label: ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghuchu'],
                data: dataAgama(datas.value),
                backgroundColor: ['#177E89', '#084C61', '#DB3A34', '#FFC857', '#997C44', '#323031']
            }
        ]
    }
})


const byJk = computed(() => {
    return {
        labels: ['Laki-laki', 'Perempuan'],
        datasets: [
            {
                // label: ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghuchu'],
                data: dataJk(datas.value),
                backgroundColor: ['#177E89', '#fe8999']
            }
        ]
    }
})

const jml = (total) => {
    return useTransition(total, { duration: 1500})
}
// const jmlSiswa = computed(() => sekolahs.value[selectedSekolah.value].siswas.length)


</script>

<template>
    <div class="toolbar flex justify-between w-full mb-6">
        <el-select v-model="selectedSekolah" >
            <el-option value="all" label="Semua Sekolah"></el-option>
            <el-option v-for="(sekolah, s) in sekolahs" :key="s" :value="s" :label="sekolah.nama"></el-option>
        </el-select>
    </div>

    <!-- {{ datas }} -->
<el-row class="w-full" :gutter="20">
    <el-col :span="16">
        <el-card>
            <template #header>
                <h3>Data Agama Siswa</h3>
            </template>
            <div class="card-body">
                <el-row>
                    <el-col :span="12">
                        <Doughnut ref="chartAgama" :options="{responsive: true}" :data="byAgama" />
                    </el-col>
                    <el-col :span="12">
                        <el-card shadow="never">
                            <h3 class="font-bold">Keterangan:</h3>
                            <table class="w-full">
                                <tr v-for="(ag,a) in ['Islam', 'Kristen', 'Katolik','Hindu','Budha', 'Konghuchu']" :key="a">
                                    <td class="border-y">{{ ag }}</td>
                                    <td class="border-y">:</td>
                                    <td class="border-y"><el-statistic :value="jml(dataAgama(datas)[a])"></el-statistic></td>
                                </tr>
                            </table>
                        </el-card>
                    </el-col>
                </el-row>
            </div>
        </el-card>
    </el-col>
    <el-col :span="8">
        <el-card>
            <template #header>
                <h3>Data Jenis Kelamin Siswa</h3>
            </template>
            <Doughnut ref="chartJk" :options="{responsive: true}" :data="byJk" />
        </el-card>
    </el-col>
</el-row>
</template>