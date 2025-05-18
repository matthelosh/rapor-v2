<script setup>
import { router, usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";
import localeData from "dayjs/plugin/localeData";
import "dayjs/locale/id";
dayjs.extend(localeData);
dayjs().localeData();
dayjs.locale("id");

const page = usePage();
const props = defineProps({
    agendas: Array,
    gurus: Array,
    weeks: Array,
});

import DashLayout from "@/Layouts/DashLayout.vue";
import { computed, defineAsyncComponent, onMounted, ref } from "vue";

const Kop = defineAsyncComponent(() => import("@/Components/Dashboard/Kop.vue"));

const months = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
];
const bulans = computed(() => {
    let res = [];
    for (let b = 0; b < 11; b++) {
        res.push(b);
    }

    return res;
});
const tahuns = computed(() => {
    const date = new Date();
    const year = date.getFullYear();
    let res = [];
    for (let y = year - 2; y < year + 2; y++) {
        res.push(y);
    }

    return res;
});

const params = route().params;
const bulanIni = computed(() => new Date().getMonth());
const selectedBulan = ref("0");
const selectedTahun = ref("2025");

const getData = async () => {
    router.get(
        route("presensi.guru.index", {
            _query: {
                sekolahId: page.props.sekolahs[0].npsn,
                bulan: selectedBulan.value + 1,
                tahun: selectedTahun.value,
            },
        }),
    );
};

const cetak = async () => {
    const lamans = document.querySelectorAll(".laman");
    let cssUrl =
        page.props.app_env == "local"
            ? "https://localhost:5173/resources/css/app.css"
            : `/build/assets/app.css`;
    let content = "";
    for (let laman in lamans) content += lamans[laman].outerHTML;
    let html = `
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Presensi ${bulans.value[selectedBulan]}</title>
            <link rel="stylesheet" href="${cssUrl}" />
        </head>
        <body>
            ${content}
        </body>
        </html>
    `;

    let win = window.open("", "_blank", "width=800,height=600");
    win.document.write(html);

    setTimeout(() => {
        // win.print();
    }, 1000);
};
onMounted(() => {
    selectedBulan.value = params.bulan ?? new Date().getMonth();
});
</script>

<template>
    <DashLayout>
        <template #header>
            <h3 class="text-lg font-bold">Presensi Guru x</h3>
        </template>
        <el-card>
            <template #header>
                <el-affix :offset="68">
                    <div class="flex items-center gap-2 bg-white">
                        <el-select placeholder="Bulan" v-model="selectedBulan">
                            <el-option
                                value="0"
                                label="Pilih Bulan"
                            ></el-option>
                            <el-option
                                v-for="b in bulans"
                                :value="b"
                                :label="months[b]"
                            ></el-option>
                        </el-select>
                        <el-select placeholder="Tahun" v-model="selectedTahun">
                            <el-option
                                v-for="t in tahuns"
                                :value="t"
                                :label="t"
                            ></el-option>
                        </el-select>
                        <el-button
                            :native-type="null"
                            type="primary"
                            @click="getData"
                            >Lihat Presensi</el-button
                        >
                        <el-button
                            :native-type="null"
                            type="primary"
                            @click="cetak"
                            >Cetak</el-button
                        >
                    </div>
                </el-affix>
            </template>
            <div>
                <div>
                    <!-- Loop Minggu dalam Bulan -->
                    <template v-for="(week, w) in weeks">
                        <div
                            class="laman my-4 p-4 border border-1 border-gray-100 shadow print:border-none print:shadow-none print:break-inside-avoid"
                        >
                            <Kop />
                            <h3
                                class="text-center uppercase font-black my-4 text-lg"
                            >
                                PRESENSI BULAN {{ months[selectedBulan] }}
                                {{ selectedTahun }} {{ params.bulan }}
                            </h3>
                            <p class="font-bold">Minggu Ke: {{ w + 1 }}</p>
                            <table class="w-[100%] border border-black">
                                <thead>
                                    <tr>
                                        <th
                                            class="border p-2 border-black"
                                            rowspan="2"
                                        >
                                            NO
                                        </th>
                                        <th
                                            class="border p-2 border-black"
                                            rowspan="2"
                                        >
                                            NAMA / NIP
                                        </th>
                                        <!-- <th
                                            class="border p-2 border-black"
                                            rowspan="2"
                                        >
                                            JABATAN
                                        </th> -->
                                        <template
                                            v-for="tanggal in week.tanggals"
                                        >
                                            <th
                                                class="border p-2 border-black"
                                                colspan="4"
                                                :class="
                                                    !tanggal.tanggal ||
                                                    tanggal.isLibur
                                                        ? 'bg-gray-400'
                                                        : ''
                                                "
                                            >
                                                {{ tanggal.hari }}, <br />
                                                {{
                                                    tanggal.tanggal
                                                        ? dayjs(
                                                              tanggal.tanggal,
                                                          ).format(
                                                              "D MMMM YYYY",
                                                          )
                                                        : ""
                                                }}
                                            </th>
                                        </template>
                                    </tr>
                                    <tr>
                                        <template
                                            v-for="tanggal in week.tanggals"
                                        >
                                            <th
                                                class="border p-2 border-black w-[60px]"
                                                :class="
                                                    !tanggal.tanggal ||
                                                    tanggal.isLibur
                                                        ? 'bg-gray-400'
                                                        : ''
                                                "
                                            >
                                                In
                                            </th>
                                            <th
                                                class="border p-2 border-black w-[80px]"
                                                :class="
                                                    !tanggal.tanggal ||
                                                    tanggal.isLibur
                                                        ? 'bg-gray-400'
                                                        : ''
                                                "
                                            >
                                                TTD
                                            </th>
                                            <th
                                                class="border p-2 border-black w-[60px]"
                                                :class="
                                                    !tanggal.tanggal ||
                                                    tanggal.isLibur
                                                        ? 'bg-gray-400'
                                                        : ''
                                                "
                                            >
                                                Out
                                            </th>
                                            <th
                                                class="border p-2 border-black w-[80px]"
                                                :class="
                                                    !tanggal.tanggal ||
                                                    tanggal.isLibur
                                                        ? 'bg-gray-400'
                                                        : ''
                                                "
                                            >
                                                TTD
                                            </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(guru, g) in props.gurus">
                                        <tr>
                                            <td
                                                class="border p-2 border-black text-center"
                                            >
                                                {{ g + 1 }}
                                            </td>
                                            <td
                                                class="border p-2 border-black text-xs"
                                            >
                                                <p class="leading-4">
                                                    {{ guru.gelar_depan ?? "" }}
                                                    {{ guru.nama }},
                                                    {{
                                                        guru.gelar_belakang ??
                                                        ""
                                                    }}
                                                </p>
                                                <p class="leading-4">
                                                    {{ guru.jabatan }}
                                                </p>
                                                <p class="leading-4">
                                                    NIP.
                                                    {{
                                                        guru.status !== "gtt"
                                                            ? guru.nip
                                                            : "-"
                                                    }}
                                                </p>
                                            </td>
                                            <!-- <td class="border p-2 border-black">
                                                <p>{{ guru.jabatan }}</p>
                                            </td> -->
                                            <template
                                                v-for="tanggal in week.tanggals"
                                            >
                                                <template v-for="i in 4">
                                                    <td
                                                        class="border border-black text-center font-bold uppercase"
                                                        :class="
                                                            !tanggal.tanggal ||
                                                            tanggal.isLibur
                                                                ? 'bg-gray-400 border-y-0'
                                                                : ''
                                                        "
                                                    >
                                                        {{
                                                            !tanggal.tanggal ||
                                                            tanggal.isLibur
                                                                ? "X"
                                                                : ""
                                                        }}
                                                    </td>
                                                </template>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div class="grid grid-cols-4 my-8">
                                <div
                                    class="col-span-1 p-4 ket border rounded-lg border-black"
                                >
                                    <p class="font-bold">Keterangan:</p>
                                    <ol class="pl-4 list-decimal">
                                        <template v-for="agenda in agendas">
                                            <li
                                                v-if="
                                                    (dayjs(
                                                        agenda.mulai,
                                                    ).month() ==
                                                        selectedBulan ||
                                                        dayjs(
                                                            agenda.selesai,
                                                        ).month() ==
                                                            selectedBulan) &&
                                                    dayjs(
                                                        agenda.mulai,
                                                    ).year() == selectedTahun
                                                "
                                            >
                                                <span>
                                                    <span
                                                        class="text-red-500 font-bold"
                                                    >
                                                        {{
                                                            dayjs(
                                                                agenda.mulai,
                                                            ).format(
                                                                "D MMM YYYY",
                                                            )
                                                        }}
                                                    </span>
                                                    <span
                                                        v-if="
                                                            dayjs(
                                                                agenda.mulai,
                                                            ).diff(
                                                                dayjs(
                                                                    agenda.selesai,
                                                                ),
                                                                'd',
                                                            ) !== 0
                                                        "
                                                    >
                                                        -
                                                        {{
                                                            dayjs(
                                                                agenda.selesai,
                                                            ).format(
                                                                "D MMM YYYY",
                                                            )
                                                        }}</span
                                                    >
                                                    <span class="text-black">
                                                        : {{ agenda.deskripsi }}
                                                    </span>
                                                </span>
                                            </li>
                                        </template>
                                    </ol>
                                </div>
                                <div class="col-span-3 ttd">
                                    <table class="w-full">
                                        <tbody>
                                            <tr>
                                                <td
                                                    colspan="2"
                                                    class="text-center"
                                                >
                                                    Mengetahui,
                                                </td>
                                                <td class="px-8">
                                                    <p>
                                                        Wagir, ....
                                                        {{
                                                            months[
                                                                selectedBulan
                                                            ]
                                                        }}
                                                        {{ selectedTahun }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-8">
                                                    <p>&nbsp;</p>
                                                    <p>Korwil,</p>
                                                    <br /><br /><br /><br />
                                                    <p
                                                        class="font-bold underline leading-4 tracking-wider"
                                                    >
                                                        {{
                                                            page.props.pejabat
                                                                ?.korwil
                                                        }}
                                                    </p>
                                                    <p class="leading-4">
                                                        NIP.
                                                        {{
                                                            page.props.pejabat
                                                                ?.nip_korwil
                                                        }}
                                                    </p>
                                                </td>
                                                <td class="px-8">
                                                    <p>&nbsp;</p>
                                                    <p>Pendamping Sekolah,</p>
                                                    <br /><br /><br /><br />
                                                    <p
                                                        class="font-bold underline leading-4 tracking-wider"
                                                    >
                                                        {{
                                                            page.props.pejabat
                                                                ?.pengawas
                                                        }}
                                                    </p>
                                                    <p class="leading-4">
                                                        NIP.
                                                        {{
                                                            page.props.pejabat
                                                                ?.nip_pengawas
                                                        }}
                                                    </p>
                                                </td>
                                                <td class="px-8">
                                                    <p>Kepala Sekolah,</p>
                                                    <br /><br /><br /><br />
                                                    <p
                                                        class="font-bold underline leading-4 tracking-wider"
                                                    >
                                                        {{
                                                            page.props
                                                                .sekolahs[0].ks
                                                                .nama
                                                        }},
                                                        {{
                                                            page.props
                                                                .sekolahs[0].ks
                                                                .gelar_belakang
                                                        }}
                                                    </p>
                                                    <p class="leading-4">
                                                        NIP.
                                                        {{
                                                            page.props
                                                                .sekolahs[0].ks
                                                                .nip
                                                        }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </el-card>
    </DashLayout>
</template>
