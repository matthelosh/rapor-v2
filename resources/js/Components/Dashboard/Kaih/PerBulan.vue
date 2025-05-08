<script setup>
import { defineAsyncComponent, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { cssUrl } from "@/helpers/utils.js";
import { Icon } from "@iconify/vue";
import dayjs from "dayjs";
import "dayjs/locale/id";

const page = usePage();
const props = defineProps({
    siswa: Object,
    rombel: Object,
    bulanTahun: String,
});

const LineChart = defineAsyncComponent(() => import("./PerMingguChart.vue"));
const InputRekap = defineAsyncComponent(() => import("./InputRekap.vue"));

const formRekap = ref(false);
const inputRekap = () => {
    formRekap.value = true;
};

const printRekap = () => {
    // window.print();

    let html = `<!doctype html>
        <html>
            <head>
                <title>Progress Pelaksanaan KAIH </title>
                <link rel="stylesheet" href="${cssUrl()}" />
            </head>
            <body class="p-0 m-0">
                <div class="py-4 grid grid-cols-6 w-full kop border-b border-b-4 border-double border-b-black font-['Roman']">
                    <div class="logo col-span-1 flex items-center justify-center">
                        <img src="/img/malangkab.png" alt="Kab Malang" class="w-[100px]" />
                    </div>
                    <div class="konten col-span-4">
                        <h2 class="text-center text-2xl font-bold uppercase">Pemerintah ${page.props.sekolahs[0].kabupaten}</h2>
                        <h2 class="text-center text-xl font-bold uppercase">Dinas Pendidikan</h2>
                        <h2 class="text-center tetx-xl font-bold uppercase">Korwil Kecamatan ${page.props.sekolahs[0].kecamatan}</h2>
                        <h2 class="text-center text-3xl font-bold uppercase"> ${page.props.sekolahs[0].nama}</h2>
                        <p class="text-center text-sm print:text-md"> ${page.props.sekolahs[0].alamat}, ${page.props.sekolahs[0].desa}, Kode pos ${page.props.sekolahs[0].kode_pos}</p>
                        <p class="text-center text-sm print:text-md"> Email: ${page.props.sekolahs[0].email}, Website: ${page.props.sekolahs[0].website ?? "-"}</p>
                    </div>
                    <div clas="col-span-1"></div>
                </div>
                <div class="main w-full">
                    <h3 class="text-center font-bold uppercase underline text-xl my-8">Laporan 7 Kebiasaan Anak Indonesia Hebat</h3>
                    <table class="mb-4">
                        <tbody>
                            <tr>
                                <td>Nama Siswa</td>
                                <td>:</td>
                                <td>${props.siswa.nama}</td>
                            </tr>
                            <tr>
                                <td>NISN</td>
                                <td>:</td>
                                <td>${props.siswa.nisn}</td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>:</td>
                                <td>${props.rombel.label}</td>
                            </tr>
                            <tr>
                                <td>Bulan</td>
                                <td>:</td>
                                <td>${dayjs(props.bulanTahun + "-01")
                                    .locale("id")
                                    .format("MMMM YYYY")}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="border border-black p-2">Kebiasaan</th>
                                <th class="border border-black p-2">Belum Terbiasa</th>
                                <th class="border border-black p-2">Mulai Terbiasa</th>
                                <th class="border border-black p-2">Sudah Terbiasa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-black p-2">
                                    Bangun Pagi
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Bangun Pagi"] <= 15 ? "&#10003;" : ""}

                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Bangun Pagi"] > 15 && props.siswa.kebiasaan_count["Bangun Pagi"] < 23 ? "&#10003" : ""}
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Bangun Pagi"] > 23 ? "&#10003" : ""}
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-black p-2">
                                    Beribadah
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Beribadah"] <= 15 ? "&#10003;" : ""}

                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Beribadah"] > 15 && props.siswa.kebiasaan_count["Beribadah"] < 23 ? "&#10003" : ""}
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Beribadah"] > 23 ? "&#10003" : ""}
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-black p-2">
                                Berolahraga
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Berolahraga"] <= 15 ? "&#10003;" : ""}

                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Berolahraga"] > 15 && props.siswa.kebiasaan_count["Berolahraga"] < 23 ? "&#10003" : ""}
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Berolahraga"] > 23 ? "&#10003" : ""}
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-black p-2">
                                Makan Sehat dan Bergizi
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Makan Sehat dan Bergizi"] <= 15 ? "&#10003;" : ""}

                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Makan Sehat dan Bergizi"] > 15 && props.siswa.kebiasaan_count["Makan Sehat dan Bergizi"] < 23 ? "&#10003" : ""}
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Makan Sehat dan Bergizi"] > 23 ? "&#10003" : ""}
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-black p-2">
                                Gemar Belajar
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Gemar Belajar"] <= 15 ? "&#10003;" : ""}

                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Gemar Belajar"] > 15 && props.siswa.kebiasaan_count["Gemar Belajar"] < 23 ? "&#10003" : ""}
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Gemar Belajar"] > 23 ? "&#10003" : ""}
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-black p-2">
                                Bermasyarakat
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Bermasyarakat"] <= 15 ? "&#10003;" : ""}

                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Bermasyarakat"] > 15 && props.siswa.kebiasaan_count["Bermasyarakat"] < 23 ? "&#10003" : ""}
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Bermasyarakat"] > 23 ? "&#10003" : ""}
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-black p-2">
                                Tidur Awal
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Tidur Cepat"] <= 15 ? "&#10003;" : ""}

                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Tidur Cepat"] > 15 && props.siswa.kebiasaan_count["Tidur Cepat"] < 23 ? "&#10003" : ""}
                                </td>
                                <td class="border border-black p-2 text-center">
                                    ${props.siswa.kebiasaan_count["Tidur Cepat"] > 23 ? "&#10003" : ""}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="ttd grid grid-cols-3 my-16">
                        <div class="ttd-guru">
                            <p>Menyetujui,</p>

                            <p class="font-black underline mt-16">
                                ${page.props.auth.user.userable.nama},
                                ${page.props.auth.user.userable.gelar_belakang}
                            </p>
                            <p>NIP. ${page.props.auth.user.userable.nip}</p>
                        </div>
                        <div class="kosong"></div>
                        <div class="ttd-ortu">
                            <p>Mengetahui,</p>

                            <p class="font-black underline mt-16">
                                ......................................................................
                            </p>
                            <p>Orang tua / Wali</p>
                        </div>
                    </div>
                </div>
            </body>
        </html>
        `;
    let win = window.open("", "_blank", "width=900,height=800");
    win.document.open();
    win.document.write(html);
    win.document.close();
    setTimeout(() => {
        win.print();
        win.close();
    }, 1000);
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
    <div class="bg-white p-8 rounded w-full sm:w-[60%] mx-auto">
        <div class="toolbar flex items-center justify-center">
            <el-button class="uppercase" size="small" @click="printBlanko">
                <Icon icon="mdi:form" />
                Cetak Blanko</el-button
            >
            <el-button class="uppercase" size="small" @click="inputRekap">
                <Icon icon="mdi:pencil" />
                Input Rekap</el-button
            >
            <el-button class="uppercase" size="small" @click="printRekap">
                <Icon icon="mdi:chart-line" />
                Cetak Rekap</el-button
            >
        </div>
        <div class="w-full sm:w-[600px] h-[600px] mx-auto py-8">
            <LineChart :bulan="bulanTahun" :rombel="rombel" :siswa="siswa" />
        </div>
    </div>
    <el-dialog v-model="formRekap" v-if="formRekap">
        <template #header>
            <div class="h3">
                Formulir Rekap Kegiatan {{ siswa.nama }} Bulan {{ bulanTahun }}
            </div>
        </template>
        <InputRekap :siswa="siswa" :rombel="rombel" :bulan="bulanTahun" />
    </el-dialog>
</template>
