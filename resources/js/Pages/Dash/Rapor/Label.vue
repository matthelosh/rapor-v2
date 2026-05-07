<script setup>
import { Icon } from "@iconify/vue";
import { cssUrl } from "@/helpers/utils";
const props = defineProps({
    rombels: Array,
});

import DashLayout from "@/Layouts/DashLayout.vue";

const cetak = () => {
    const el = document.querySelector(".page");
    const printWindow = window.open("", "_blank", "width=900,height=600");
    printWindow.document.write(`
        <html>
            <head>
                <title>Label Nama</title>
                <link rel="stylesheet" href="${cssUrl()}">
            </head>
            <body>
                ${el.outerHTML}
            </body>
        </html>
    `);
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
};
</script>

<template>
    <div>
        <DashLayout title="Label Nama">
            <el-card>
                <template #header>
                    <div class="flex items-center justify-between">
                        <span>Label Nama</span>
                        <el-button @click="cetak">
                            <Icon icon="mdi:printer" />
                            Cetak
                        </el-button>
                    </div>
                </template>
                <div>
                    <div class="flex gap-2 flex-wrap w-full bg-slate-100 page">
                        <template v-for="(siswa, r) in rombels[0].siswas">
                            <div
                                class="flex justify-center items-center flex-col w-[8cm] bg-white h-[2cm] border-4 border-double border-black break-inside-avoid"
                            >
                                <span class="font-bold text-lg text-center">{{
                                    siswa.nama
                                }}</span>
                                <span class="text-sm"
                                    >No. Induk: {{ siswa.nis ?? "-" }} /
                                    {{ siswa.nisn }}</span
                                >
                            </div>
                        </template>
                    </div>
                </div>
            </el-card>
        </DashLayout>
    </div>
</template>
