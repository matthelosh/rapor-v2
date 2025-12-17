<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import axios from "axios";
import dayjs from "dayjs";
import "dayjs/locale/id";

import Kop from "@/Components/Dashboard/Kop.vue";
const page = usePage();
const props = defineProps({ siswa: Object, rombel: Object });

const kebiasaans = ref([
    "Bangun Pagi",
    "Beribadah",
    "Berolahraga",
    "Makan Sehat dan Bergizi",
    "Gemar Belajar",
    "Bermasyarakat",
    "Tidur Cepat",
]);
const rekap = ref(null);
const jmlHari = computed(() => {
    const semester = page.props.periode.semester.kode;
    const tahun =
        semester == "1"
            ? page.props.periode.tapel.label.split("/")[0]
            : page.props.periode.tapel.label.split("/")[1];
    const startDate =
        semester == "1" ? dayjs(tahun + "-07-01") : dayjs(tahun + "-01-01");
    const endDate =
        semester == "1" ? dayjs(tahun + "-12-31") : dayjs(tahun + "-06-30");

    return endDate.diff(startDate, "day") + 1;
});

const prosentase = (nilai) => {
    return Math.round((nilai / jmlHari.value) * 100);
};

const cetak = async () => {
    const cssUrl =
        page.props.app_env == "local"
            ? page.props.appUrl + ":5173/resources/css/app.css"
            : "/build/assets/app.css";
    const element = document.querySelector(".page");
    const html = `<!doctype html>
            <html>
                <head>
                    <title>Rekap 7 KAIH ${props.siswa.nama}-${props.siswa.nisn}</title>
                    <link rel="stylesheet" href="${cssUrl}"
                </head>
                <body>
                    ${element.outerHTML}
                </body>
            </html>
        `;
    let win = window.open("", "_blank", "width=900,height=800");
    win.document.open();
    win.document.write(html);
    win.document.close();
    setTimeout(() => {
        win.print();
    }, 1000);
};

const getKaihSiswa = () => {
    axios
        .get(
            route("dashboard.kaih.rekap.siswa", {
                _query: {
                    rombelId: props.rombel.kode,
                    semester: page.props.periode.semester.kode,
                    siswaId: props.siswa.nisn,
                },
            }),
        )
        .then((res) => (rekap.value = res.data.rekap));
};

onBeforeMount(() => {
    getKaihSiswa();
});
</script>
<template>
    <div class="wrapper">
        <div
            class="toolbar h-12 bg-slate-100 flex items-center justify-between px-4"
        >
            <h3 class="font-bold text-slate-800">Cetak Formulir Pemantauan</h3>
            <div class="flex items-center gap-2">
                <el-button @click="cetak">
                    <Icon icon="mdi:printer" />
                </el-button>
            </div>
        </div>
        <div class="content py-4 px-6 bg-slate-200">
            <div
                class="page bg-white p-4 w-[90%] xl:w-[60%] mx-auto rounded shadow print:w-full print:rounded-none print:shadow-none"
            >
                <Kop />
                <h3 class="text-center text-2xl font-black uppercase mt-8">
                    Formulir Pemantauan <br />
                    Tujuh Kebiasaan Anak Indonesia Hebat
                </h3>
                <h4 class="text-center uppercase font-bold">
                    Semester {{ page.props.periode.semester.label }} Tahun
                    {{ page.props.periode.tapel.label }}
                </h4>
                <!-- <div class="text-center">{{ page.props.appName }}</div> -->
                <table class="my-4">
                    <tbody>
                        <tr>
                            <td>Nama Anak</td>
                            <td>:</td>
                            <td>{{ siswa.nama }}</td>
                        </tr>
                        <tr>
                            <td>Nama Sekolah</td>
                            <td>:</td>
                            <td>{{ page.props.sekolahs[0]?.nama }}</td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>:</td>
                            <td>{{ rombel.label }}</td>
                        </tr>
                        <!-- <tr>
                            <td>Bulan</td>
                            <td>:</td>
                            <td></td>
                        </tr> -->
                    </tbody>
                </table>
                <!-- <div>
                    {{ rekap }}
                </div> -->
                <table class="w-full" v-if="rekap">
                    <thead>
                        <tr>
                            <th class="border border-black p-2" rowspan="2">
                                No
                            </th>
                            <th class="border border-black p-2" rowspan="2">
                                Kebiasaan
                            </th>
                            <th class="border border-black p-2" colspan="3">
                                Penerapan Tujuh Kebiasaan
                            </th>
                        </tr>
                        <tr>
                            <th class="border border-black p-2">
                                Belum Terbiasa
                            </th>
                            <th class="border border-black p-2">
                                Mulai Terbiasa
                            </th>
                            <th class="border border-black p-2">
                                Sudah Terbiasa
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="(key, k) in Object.keys(rekap)"
                            :key="`key-${k}`"
                        >
                            <tr>
                                <td class="border border-black text-center p-2">
                                    {{ k + 1 }}
                                </td>
                                <td class="border border-black left p-2">
                                    {{ key }}
                                </td>
                                <td class="border border-black text-center p-2">
                                    <!-- {{ prosentase(rekap[key]) }} |
                                    {{ rekap[key] }} | {{ key }} -->
                                    <Icon
                                        v-if="
                                            prosentase(rekap[key]) <= 33 &&
                                            rekap[key]
                                        "
                                        icon="mdi:check"
                                        class="mx-auto text-2xl text-red-500"
                                    />
                                </td>
                                <td class="border border-black text-center p-2">
                                    <Icon
                                        v-if="
                                            rekap[key] &&
                                            prosentase(rekap[key]) > 33 &&
                                            prosentase(rekap[key]) <= 66
                                        "
                                        icon="mdi:check"
                                        class="mx-auto text-2xl text-green-500"
                                    />
                                </td>
                                <td class="border border-black text-center p-2">
                                    <Icon
                                        v-if="
                                            rekap[key] &&
                                            prosentase(rekap[key]) > 66 &&
                                            prosentase(rekap[key]) <= 100
                                        "
                                        icon="mdi:check"
                                        class="mx-auto text-2xl text-blue-500"
                                    />
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- {{ siswa.kaihs }} -->

                <div class="ttd grid grid-cols-3 my-16">
                    <div class="ttd-guru">
                        <p>Menyetujui,</p>

                        <p class="font-black underline mt-16">
                            {{ rombel.wali_kelas.nama }},
                            {{ rombel.wali_kelas.gelar_belakang }}
                        </p>
                        <p>NIP. {{ rombel.wali_kelas.nip }}</p>
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
        </div>
    </div>
</template>
