<script setup>
import { Icon } from '@iconify/vue';
import { usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import 'dayjs/locale/id'
dayjs.locale('id')
import html2pdf from 'html2pdf.js';
import html2canvas from 'html2canvas';
import jsPDF from 'jspdf';
import { computed } from 'vue';
import QRCodeVue3 from "qrcode-vue3";

const page = usePage()
const props = defineProps({sertifikat: Object})
const emit = defineEmits(['close'])

const cetak = async() => {
    let win = window.open("", "_blank", "width=1000,height=700")
    const element= document.querySelector(".cetak")
    let cssUrl = page.props.app_env == 'local' ? 'https://localhost:5173/resources/css/app.css' : `/build/assets/app.css`

    let html = `<!doctype html>
                <html>
                    <head>
                        <title>Sertifikat ${props.sertifikat.workshop.nama} - ${props.sertifikat.penerima.nama}</title> 
                        <link rel="stylesheet" href="${cssUrl}" />   
                        <style>
                            body {
                                background: url('${bg.value}')!important;
                                background-size: cover!important;
                                background-repeat: no-repeat;
                            }
                        </style>
                    </head>
                    <body>${element.outerHTML}</body>
                </html>

    `
    await win.document.write(html)

    setTimeout(() => {
        win.print()
    }, 1000);
}

const bg = computed(() => {
    return '/img/bg-sertifikat-1.svg'
})

const unduh = () => {
    const element= document.querySelector(".cetak")
    const optios = {
        filename: 'Sertifikat',
        margin:0,
        image: { type: 'jpeg', quality: 0.98},
        html2canvas: {scale: 2},
        pagebreak: {mode: 'avoid-all', avoid: '.cetak'},
        jsPDF:{
            unit: 'in',
            format: 'a4',
            orientation: 'landscape'
        }
    }

    html2pdf().from(element).set(optios).save()
}
</script>

<template>
<el-card>
    <template #header>
        <div class="flex items-center justify-between">
            <h3>Sertifikat {{ sertifikat.workshop.nama }}</h3>
            <div class="flex items-center">
                <el-button-group class="mr-2">
                    <el-button type="primary" @click="unduh">
                        <Icon icon="mdi:download-box" class="mr-1"  />
                        unduh
                    </el-button>
                    <el-button type="success" @click="cetak">
                        <Icon icon="mdi:printer" class="mr-1"  />
                        Cetak
                    </el-button>
                </el-button-group>

                <el-button type="danger" @click="emit('close')" circle>
                    <Icon icon="mdi:close" />
                </el-button>
            </div>
        </div>
    </template>
    <div class="cetak">
        <div class=" p-10 shadow-lg print:shadow-none rounded w-[27cm] h-[21cm] mx-auto bg-cover bg-no-repeat relative" :class="`bg-[url('${bg}')]`">
            <!-- <vue-qrcode value="Halo" class="absolute" /> -->
            <div class="qr absolute p-3 bg-white rounded print:-left-[50px]">
             <QRCodeVue3 
                :height="80" 
                :width="80" 
                :value="sertifikat.nomor"  />
            </div>
            <img src="/img/tutwuri.png" alt="Tutwuri" class="w-[80px] mx-auto">

            <h1 class="text-center text-4xl font-bold text-blue-900 mt-8 tracking-widest">SERTIFIKAT</h1>
            <h3 class="text-center text-lg font-bold p-2 bg-yellow-300 w-[40%] mx-auto">Nomor: {{ sertifikat.nomor }}</h3>
            <h3 class="text-center mt-6 text-lg">Diberikan Kepada:</h3>

            <h1 class="text-center text-4xl underline mt-4 font-black">{{ sertifikat.penerima.nama }}, {{ sertifikat.penerima.gelar_belakang }}</h1>
            <p class="text-center text-2xl tracking-wide">NIP. {{ sertifikat.penerima.nip }}</p>

            <h3 class="text-center text-xl p-2 bg-sky-200 w-[50%] mx-auto font-bold mt-4">{{ sertifikat.penerima.sekolahs[0].nama }}</h3>

            <p class="text-center w-[70%] mx-auto text-lg mt-6">{{ sertifikat.deskripsi }}</p>

            <div class="grid grid-cols-6 gap-4 w-[80%] mx-auto mt-10">
                <div class="ttd col-span-3">
                    <p>Mengetahui,</p>
                    <p>Korwil Dinas Pendidikan Kecamatan Wagir</p>

                    <p class="underline font-bold mt-20">ABDUL MANAB, S. Pd., M.M.</p>
                    <p>NIP. </p>
                </div>
                <div class="ttd col-span-1"></div>
                <div class="ttd col-span-2">
                    <p>Wagir, 29 September 2024</p>
                    <p>Panitia,</p>

                    <p class="underline mt-20 font-bold uppercase">{{ sertifikat.workshop.pelaksana }}</p>
                    <p>NIP. </p>
                </div>
            </div>
        </div>
    </div>
</el-card>

</template>

<style scoped>
</style>