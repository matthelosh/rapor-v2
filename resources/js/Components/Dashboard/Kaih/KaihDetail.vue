<script setup>
import { ref } from "vue";
import Radar from "./RadarChart.vue";
// import BarChart from "./KebiasaanBarChart.vue";

const props = defineProps({
    siswa: Object,
    rombel: Object,
    bulanTahun: String,
});
</script>

<template>
    <div>
        <Radar :siswa="siswa" :bulanTahun="bulanTahun" />
        <!-- <BarChart :siswa="siswa" /> -->
        <!-- {{ siswa }} -->
    </div>
</template>
