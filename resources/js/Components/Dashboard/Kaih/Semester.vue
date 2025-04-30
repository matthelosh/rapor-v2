<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import axios from "axios";
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
// const lookup = props.siswa.kaihs.reduce((acc, cur) => {
//     acc[cur.kebiasaan] = cur.waktu;
//     return acc;
// }, {});
// const items = computed(() => {
//     return kebiasaans.value.map((keb) => ({
//         keb,
//         nilai: lookup[keb] ?? null,
//     }));
// });

const cetak = async () => {};

const getKaihSiswa = () => {
    axios
        .get(
            route("dashboard.kaih.rekap.siswa", {
                _query: {
                    rombelId: props.rombel.kode,
                    semester: "1",
                    siswaId: props.siswa.nisn,
                },
            }),
        )
        .then((res) => console.log(res));
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
                class="page bg-white p-4 w-[60%] mx-auto rounded shadow print:w-full print:rounded-none print:shadow-none"
            >
                <Kop />
                <h3 class="text-center text-2xl font-black uppercase my-8">
                    Formulir Pemantauan <br />
                    7 Kebiasaan Anak Indonesia Hebat
                </h3>

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
                            <td>SD Contoh</td>
                        </tr>
                        <tr>
                            <td>Kelas</td>
                            <td>:</td>
                            <td>{{ rombel.label }}</td>
                        </tr>
                        <tr>
                            <td>Bulan</td>
                            <td>:</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <table class="w-full">
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
                        <!-- <template v-for="(kaih, k) in items" :key="`keb-${k}`">
                            <tr>
                                <td class="border border-black text-center p-2">
                                    {{ k + 1 }}
                                </td>
                                <td class="border border-black left p-2">
                                    {{ kaih.keb }}
                                </td>
                                <td class="border border-black text-center p-2">
                                    {{ kaih.nilai }}
                                    <Icon
                                        icon="mdi:check-circle-outline"
                                        class="mx-auto text-xl text-red-500"
                                    />
                                </td>
                                <td class="border border-black text-center p-2">
                                    <Icon
                                        icon="mdi:check-circle-outline"
                                        class="mx-auto text-xl text-green-500"
                                    />
                                </td>
                                <td class="border border-black text-center p-2">
                                    <Icon
                                        icon="mdi:check-circle-outline"
                                        class="mx-auto text-xl text-blue-500"
                                    />
                                </td>
                            </tr>
                        </template> -->
                    </tbody>
                </table>
                <div class="ttd grid grid-cols-3 my-16">
                    <div class="ttd-guru">
                        <p>Menyetujui,</p>

                        <p class="font-black underline mt-16">
                            {{ page.props.auth.user.userable.nama }},
                            {{ page.props.auth.user.userable.gelar_belakang }}
                        </p>
                        <p>NIP. {{ page.props.auth.user.userable.nip }}</p>
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
