<script setup>
import { defineAsyncComponent } from "vue";

import { Icon } from "@iconify/vue";
const props = defineProps({
    siswa: Object,
    rombel: Object,
    bulan: String,
});

const LineChart = defineAsyncComponent(() => import("./PerMingguChart.vue"));

const inputRekap = () => {};

const printRekap = () => {
    // window.print();
};

const printBlanko = () => {
    const linkBlanko = document.createElement("a");
    linkBlanko.href =
        "/files/" +
        (parseInt(props.rombel.tingkat) < 5
            ? "blanko_kaih_1_4.pdf"
            : "blanko_kaih_5_6.pdf");
    linkBlanko.target = "_blank";
    linkBlanko.click();
};
</script>
<template>
    <div class="toolbar flex items-center justify-center">
        <el-button class="uppercase" size="small" @click="inputRekap">
            <Icon icon="mdi:pencil" />
            Input Rekap</el-button
        >
        <el-button class="uppercase" size="small" @click="printRekap">
            <Icon icon="mdi:chart-line" />
            Cetak Rekap</el-button
        >
        <el-button class="uppercase" size="small" @click="printBlanko">
            <Icon icon="mdi:form" />
            Cetak Blanko</el-button
        >
    </div>
    <div class="w-[600px] h-[600px] mx-auto py-8">
        <LineChart :bulan="bulan" :rombel="rombel" :siswa="siswa" />
    </div>
</template>
