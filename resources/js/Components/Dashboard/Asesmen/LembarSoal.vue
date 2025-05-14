<script setup>
import { ref, computed, onBeforeMount, defineAsyncComponent } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import dayjs from "dayjs";
import "dayjs/locale/id";
dayjs.locale("id");

const FormSoal = defineAsyncComponent(
    () => import("@/Components/Dashboard/Asesmen/FormSoal.vue"),
);

const page = usePage();
const props = defineProps({ selectedAsesmen: Object });
const emit = defineEmits(["close"]);
const show = ref(false);
const soals = ref([]);
const showFormSoal = ref(false);

import Kop from "@/Components/Umum/Kop.vue";

const allSoals = ref([]);
const getAllSoals = async () => {
    await axios
        .get(
            route("dashboard.soal.all", {
                tingkat: props.selectedAsesmen.kelas,
                mapel_id: props.selectedAsesmen.mapel_id,
                agama:
                    props.selectedAsesmen.mapel_id == "pabp"
                        ? props.selectedAsesmen.agama
                        : null,
                asesmen_id: props.selectedAsesmen.id,
                semester: props.selectedAsesmen.semester.kode,
            }),
        )
        .then((res) => {
            allSoals.value = res.data.soals;
        });
};

const cetakLembarSoal = async () => {
    let win = window.open("", "_blank", "width=1024,height=1080");
    const cssUrl =
        page.props.app_env == "local"
            ? "https://raporsd.test:5173/resources/css/app.css"
            : "/build/assets/app.css";
    let elemen = document.querySelector(".cetak");

    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Lembar Soal ${props.selectedAsesmen.nama}</title>
                <link href="${cssUrl}" rel="stylesheet" />
            </head>

            <body>
                ${elemen.outerHTML}
            </body>
        </html>
    `;
    win.document.write(html);
    setTimeout(() => {
        win.print();
        // win.close();
    }, 1000);
};

const drag = (ev, item) => {
    ev.dataTransfer.dropEffect = "move";
    ev.dataTransfer.effectAllowed = "move";
    ev.dataTransfer.setData("itemId", item.id);
};
const dragOver = (ev) => {
    const dropZone = document.querySelector(".drop-zone");
    dropZone.classList.toggle("bg-sky-100");
};
const drop = (ev) => {
    const itemID = ev.dataTransfer.getData("itemID");
    attachSoal(itemID);
    const dropZone = document.querySelector(".drop-zone");
    dropZone.classList.remove("bg-sky-100");
};

const attachSoal = async (soalId) => {
    router.post(
        route("dashboard.asesmen.soal.attach", {
            id: props.selectedAsesmen.id,
        }),
        { soalId: soalId },
        {
            onFinish: () => {
                getAllSoals();
                const item = allSoals.value.find((soal) => soal.id == soalId);
                soals.value.push(item);
                router.reload({ only: ["asesmens"], preserveState: true });
            },
        },
    );
};

const jmlPG = computed(() => {
    return props.selectedAsesmen.soals.filter((soal) => soal.tipe == "pilihan")
        .length;
});

const detachSoal = (item, indexSoal) => {
    router.post(
        route("dashboard.asesmen.soal.detach", {
            id: props.selectedAsesmen.id,
        }),
        { soalId: item.id },
        {
            onFinish: () => {
                getAllSoals();
                soals.value.splice(indexSoal, 1);
                router.reload({ preserveState: true });
            },
        },
    );
};

const showMiniBankSoal = ref(false);
const showBank = () => {
    showMiniBankSoal.value = true;
};
// Add Soal
const addSoal = () => {
    showFormSoal.value = true;
};
const closeFormSoal = (item) => {
    showFormSoal.value = false;
    // console.log(item)
};

onBeforeMount(() => {
    show.value = props.selectedAsesmen !== null;
    getAllSoals();
    soals.value = props.selectedAsesmen.soals;
});
</script>

<template>
    <el-dialog
        v-model="show"
        fullscreen
        :show-close="false"
        style="min-height: 100vh !important"
    >
        <template #header>
            <div class="flex items-center h-full w-full justify-between">
                <h3>Lembar Soal Asesmen: {{ props.selectedAsesmen.nama }}</h3>
                <div class="flex items-center">
                    <el-button
                        class="mr-1"
                        type="primary"
                        @click="cetakLembarSoal"
                    >
                        <Icon icon="mdi-printer" class="mr-1" />
                        Cetak
                    </el-button>
                    <el-button circle type="danger" @click="emit('close')">
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <template #default>
            <el-row :gutter="20">
                <el-col :span="16" :xs="24">
                    <el-affix :offset="0">
                        <div
                            class="flex w-full border shadow-md items-center mb-2 p-2 bg-white h-12 justify-end hidden-sm-and-up"
                        >
                            <Icon
                                icon="mdi:menu"
                                class="text-xl hover:cursor-pointer"
                                @click="showBank"
                            />
                        </div>
                    </el-affix>
                    <div>
                        <FormSoal
                            v-if="showFormSoal"
                            :selectedAsesmen="props.selectedAsesmen"
                            @close="closeFormSoal"
                            @stored="getAllSoals"
                        />
                        <div class="cetak" v-else>
                            <div
                                class="mb-4 break-after-page px-4 border print:border-none"
                            >
                                <div class="text-black">
                                    <div class="soal">
                                        <Kop class="hidden-sm-and-down" />
                                        <h3
                                            class="text-center md:text-lg print:text-md print:leading-5 font-bold font-serif mt-8"
                                        >
                                            Lembar Soal
                                            {{ props.selectedAsesmen.nama }}
                                        </h3>
                                        <h3
                                            class="text-center md:text-lg print:text-md print:leading-5 font-bold font-serif"
                                        >
                                            Semester
                                            {{
                                                props.selectedAsesmen.semester
                                                    .label
                                            }}
                                            {{
                                                props.selectedAsesmen.tapel
                                                    .deskripsi
                                            }}
                                        </h3>

                                        <table
                                            class="md:w-[40%] print:w-[60%] border mx-auto my-4"
                                        >
                                            <tbody>
                                                <tr>
                                                    <td>Mata Pelajaran</td>
                                                    <td>:</td>
                                                    <td>
                                                        {{
                                                            props
                                                                .selectedAsesmen
                                                                .mapel.label
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Kelas</td>
                                                    <td>:</td>
                                                    <td>
                                                        {{
                                                            props
                                                                .selectedAsesmen
                                                                .rombel
                                                                ? props
                                                                      .selectedAsesmen
                                                                      .rombel
                                                                      .label
                                                                : props
                                                                      .selectedAsesmen
                                                                      .kelas
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal</td>
                                                    <td>:</td>
                                                    <td>
                                                        {{
                                                            dayjs(
                                                                props
                                                                    .selectedAsesmen
                                                                    .tanggal,
                                                            ).format(
                                                                "DD MMM YYYY",
                                                            )
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Alokasi Waktu</td>
                                                    <td>:</td>
                                                    <td>
                                                        {{
                                                            props
                                                                .selectedAsesmen
                                                                .mulai
                                                        }}
                                                        s/d
                                                        {{
                                                            props
                                                                .selectedAsesmen
                                                                .selesai
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Jumlah Soal</td>
                                                    <td>:</td>
                                                    <td>
                                                        {{
                                                            props
                                                                .selectedAsesmen
                                                                .soals.length
                                                        }}
                                                        Butir
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div
                                            class="drop-zone"
                                            @drop="drop($event)"
                                            @dragover.prevent
                                            @dragenter.prevent="
                                                dragOver($event)
                                            "
                                        >
                                            <div
                                                v-if="
                                                    props.selectedAsesmen.soals
                                                        .length < 1
                                                "
                                                class="w-full h-[300px] bg-sky-50 flex items-center justify-center"
                                            >
                                                <p
                                                    class="font-bold text-lg text-slate-600 font-mono"
                                                >
                                                    Seret Soal Ke Sini
                                                </p>
                                            </div>
                                            <div v-if="soals.length > 0">
                                                <div class="pilihan">
                                                    <h1
                                                        class="font-bold text-lg mb-2 mt-8"
                                                    >
                                                        I. Pilihan Ganda
                                                    </h1>
                                                    <p
                                                        class="italic mb-4 text-md font-serif"
                                                    >
                                                        Petunjuk: Pilih jawaban
                                                        yang benar!
                                                    </p>
                                                    <template
                                                        v-for="(
                                                            soal, s
                                                        ) in soals.filter(
                                                            (soal) =>
                                                                soal.tipe ==
                                                                'pilihan',
                                                        )"
                                                    >
                                                        <ul class="tes">
                                                            <li
                                                                class="mb-4 hover:bg-slate-100 relative group"
                                                            >
                                                                <Icon
                                                                    icon="mdi:trash-can"
                                                                    class="text-4xl text-red-400 absolute right-4 hover:cursor-pointer md:hidden group-hover:block transition-all ease-in-out top-2"
                                                                    @click="
                                                                        detachSoal(
                                                                            soal,
                                                                            s,
                                                                        )
                                                                    "
                                                                />
                                                                <div
                                                                    class="flex gap-2"
                                                                >
                                                                    {{ s + 1 }}.
                                                                    <span
                                                                        v-html="
                                                                            soal.pertanyaan
                                                                        "
                                                                    ></span>
                                                                </div>
                                                                <span
                                                                    class="md:flex gap-6 ml-4"
                                                                >
                                                                    <div
                                                                        class="flex gap-1 ml-4 mb-2"
                                                                    >
                                                                        a.
                                                                        <span
                                                                            v-html="
                                                                                soal.a
                                                                            "
                                                                        ></span>
                                                                    </div>
                                                                    <div
                                                                        class="flex gap-1 ml-4 mb-2"
                                                                    >
                                                                        b.
                                                                        <span
                                                                            v-html="
                                                                                soal.b
                                                                            "
                                                                        ></span>
                                                                    </div>
                                                                    <div
                                                                        class="flex gap-1 ml-4 mb-2"
                                                                    >
                                                                        c.
                                                                        <span
                                                                            v-html="
                                                                                soal.c
                                                                            "
                                                                        ></span>
                                                                    </div>
                                                                    <div
                                                                        class="flex gap-1 ml-4 mb-2"
                                                                    >
                                                                        d.
                                                                        <span
                                                                            v-html="
                                                                                soal.d
                                                                            "
                                                                        ></span>
                                                                    </div>
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </template>
                                                </div>
                                                <div
                                                    class="isian"
                                                    v-if="
                                                        soals.filter(
                                                            (soal) =>
                                                                soal.tipe ==
                                                                'isian',
                                                        ).length > 0
                                                    "
                                                >
                                                    <h1
                                                        class="font-bold text-lg mb-2 mt-8"
                                                    >
                                                        II. Soal Isian
                                                    </h1>
                                                    <p
                                                        class="italic mb-4 text-md font-serif"
                                                    >
                                                        Petunjuk: Jawab dengan
                                                        singkat!
                                                    </p>
                                                    <ul
                                                        class="list-decimal pl-4"
                                                    >
                                                        <li
                                                            v-for="(
                                                                soal, s
                                                            ) in soals.filter(
                                                                (soal) =>
                                                                    soal.tipe ==
                                                                    'isian',
                                                            )"
                                                            class="group relative my-2 hover:bg-sky-50"
                                                        >
                                                            <Icon
                                                                icon="mdi:trash-can"
                                                                class="text-2xl text-red-400 absolute right-4 hover:cursor-pointer md:hidden group-hover:block transition-all ease-in-out top-2"
                                                                @click="
                                                                    detachSoal(
                                                                        soal,
                                                                        s,
                                                                    )
                                                                "
                                                            />
                                                            <div
                                                                class="flex gap-2"
                                                            >
                                                                <span
                                                                    v-html="
                                                                        soal.pertanyaan
                                                                    "
                                                                ></span>
                                                                <p>
                                                                    ..........................................................................................
                                                                </p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div
                                                    class="uraian"
                                                    v-if="
                                                        soals.filter(
                                                            (soal) =>
                                                                soal.tipe ==
                                                                'uraian',
                                                        ).length > 0
                                                    "
                                                >
                                                    <h1
                                                        class="font-bold text-lg mb-2 mt-8"
                                                    >
                                                        III. Soal Uraian / Esay
                                                    </h1>
                                                    <p
                                                        class="italic mb-4 text-md font-serif"
                                                    >
                                                        Petunjuk: Jawab
                                                        pertanyaan dengan benar!
                                                    </p>
                                                    <ul
                                                        class="list-decimal pl-4"
                                                    >
                                                        <li
                                                            v-for="(
                                                                soal, s
                                                            ) in soals.filter(
                                                                (soal) =>
                                                                    soal.tipe ==
                                                                    'uraian',
                                                            )"
                                                            class="group relative"
                                                        >
                                                            <Icon
                                                                icon="mdi:trash-can"
                                                                class="text-2xl text-red-400 absolute right-4 hover:cursor-pointer md:hidden group-hover:block transition-all ease-in-out top-2"
                                                                @click="
                                                                    detachSoal(
                                                                        soal,
                                                                        s,
                                                                    )
                                                                "
                                                            />
                                                            <div>
                                                                <span
                                                                    v-html="
                                                                        soal.pertanyaan
                                                                    "
                                                                ></span>
                                                                <p
                                                                    class="border-b border-black border-dotted border-b-2 mr-8 mt-4 mb-2"
                                                                >
                                                                    &nbsp;
                                                                </p>
                                                                <p
                                                                    class="border-b border-black border-dotted border-b-2 mr-8 mt-4 mb-2"
                                                                >
                                                                    &nbsp;
                                                                </p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="mb-4 break-after-page px-4 border print:border-none"
                            >
                                <h3>
                                    Kunci Jawaban
                                    {{ props.selectedAsesmen.nama }}
                                </h3>
                                <div class="pilihan">
                                    <h3 class="font-bold">I. Pilihan Ganda</h3>
                                    <div class="columns-6 p-2">
                                        <div
                                            v-for="(soal, s) in soals.filter(
                                                (soal) =>
                                                    soal.tipe == 'pilihan',
                                            )"
                                        >
                                            {{ s + 1 }}. {{ soal.kunci }}
                                        </div>
                                    </div>
                                </div>
                                <div class="isian my-4">
                                    <h3 class="font-bold">
                                        II. Isian (Kebijaksanaan Guru)
                                    </h3>
                                    <ul class="pl-6 list-decimal">
                                        <li
                                            v-for="(soal, s) in soals.filter(
                                                (soal) => soal.tipe == 'isian',
                                            )"
                                        >
                                            <span v-html="soal.kunci"></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="Uraian my-4">
                                    <h3 class="font-bold">
                                        III. Uraian (Kebijaksanaan Guru)
                                    </h3>
                                    <ul class="pl-6 list-decimal">
                                        <li
                                            v-for="(soal, s) in soals.filter(
                                                (soal) => soal.tipe == 'uraian',
                                            )"
                                        >
                                            <span v-html="soal.kunci"></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div
                                class="mb-4 break-after-page relative hidden-sm-and-down px-4 border print:border-none"
                            >
                                <Kop />

                                <h3
                                    class="text-center uppercase mt-4 font-bold"
                                >
                                    Lembar Jawaban
                                </h3>
                                <h3 class="text-center uppercase font-bold">
                                    Asesmen:
                                    {{ props.selectedAsesmen.nama }} Semester
                                    {{ props.selectedAsesmen.semester.label }}
                                    {{ props.selectedAsesmen.tapel.deskripsi }}
                                </h3>
                                <h3 class="text-center uppercase font-bold">
                                    Mata Pelajaran:
                                    {{ props.selectedAsesmen.mapel.label }}
                                </h3>
                                <div class="flex justify-between mt-4">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="py-1">No. Absen</td>
                                                <td class="py-1">:</td>
                                                <td class="py-1">
                                                    ....................
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-1">NISN</td>
                                                <td class="py-1">:</td>
                                                <td class="py-1">
                                                    ..............................................
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="py-1">Nama Siswa</td>
                                                <td class="py-1">:</td>
                                                <td class="py-1">
                                                    ..............................................
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div
                                        class="nilai w-[300px] grid grid-cols-2 h-[100px]"
                                    >
                                        <div
                                            class="border border-black skor col-span-1"
                                        >
                                            <h3
                                                class="text-center bg-slate-800 text-white"
                                            >
                                                SKOR
                                            </h3>
                                        </div>
                                        <div
                                            class="border border-black ttd col-span-1"
                                        >
                                            <h3
                                                class="text-center bg-slate-800 text-white"
                                            >
                                                TTD
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <h3
                                    class="text-center bg-slate-500 uppercase text-white py-2 font-bold mt-2"
                                >
                                    Jawaban
                                </h3>
                                <p class="text-center">
                                    Beri tanda silang (X) pada pilihan jawaban
                                    yang benar!
                                </p>
                                <div class="grid grid-cols-6 gap-6 print:gap-3">
                                    <div v-for="c in 6">
                                        <table class="border w-full">
                                            <thead>
                                                <tr>
                                                    <th
                                                        class="bg-slate-400 border border-black print:w-[25px] w-[50px]"
                                                    >
                                                        No
                                                    </th>
                                                    <th
                                                        class="bg-slate-400 border border-black text-center"
                                                        colspan="4"
                                                    >
                                                        Pilhan
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="i of 5"
                                                    :class="
                                                        i + (20 / 4) * (c - 1) >
                                                        jmlPG
                                                            ? 'bg-slate-600 text-slate-600'
                                                            : ''
                                                    "
                                                >
                                                    <td
                                                        class="border border-black text-center bg-slate-300"
                                                        :class="
                                                            i +
                                                                (20 / 4) *
                                                                    (c - 1) >
                                                            jmlPG
                                                                ? 'bg-slate-600 text-slate-600'
                                                                : ''
                                                        "
                                                    >
                                                        {{
                                                            i +
                                                            (20 / 4) * (c - 1)
                                                        }}.
                                                    </td>
                                                    <td
                                                        class="border border-black text-center"
                                                    >
                                                        a
                                                    </td>
                                                    <td
                                                        class="border border-black text-center"
                                                    >
                                                        b
                                                    </td>
                                                    <td
                                                        class="border border-black text-center"
                                                    >
                                                        c
                                                    </td>
                                                    <td
                                                        class="border border-black text-center"
                                                    >
                                                        d
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <h3
                                    class="text-center bg-slate-500 uppercase text-white py-2 font-bold mt-6 my-4"
                                >
                                    Jawaban Isian
                                </h3>

                                <ul class="list-decimal pl-4">
                                    <li
                                        v-for="item of soals.filter(
                                            (soal) => soal.tipe == 'isian',
                                        ).length"
                                        class="w-full border-b border-b-slate-600 border-dotted mb-2"
                                    ></li>
                                </ul>

                                <h3
                                    class="text-center bg-slate-500 uppercase text-white py-2 font-bold mt-8"
                                >
                                    Jawaban Uraian (Essay)
                                </h3>
                                <ul class="list-decimal pl-4 mt-4">
                                    <li
                                        v-for="item of soals.filter(
                                            (soal) => soal.tipe == 'isian',
                                        ).length"
                                        class="w-full border-b border-b-slate-600 border-dotted mb-4"
                                    ></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </el-col>
                <el-col :span="8" :xs="24" class="hidden-sm-and-down">
                    <el-affix :offset="70">
                        <el-card class="mb-4">
                            <div class="flex gap-2">
                                <el-button type="primary" @click="addSoal"
                                    >Buat Soal Baru</el-button
                                >
                            </div>
                        </el-card>
                        <el-card>
                            <div class="content p-2">
                                <h3 class="font-black text-sky-800">
                                    Bank Soal Kelas
                                    {{ props.selectedAsesmen.kelas }}
                                </h3>
                                <el-scrollbar max-height="85vh">
                                    <el-divider>
                                        <h3 class="font-bold text-sky-700">
                                            Pilihan Ganda
                                        </h3>
                                    </el-divider>
                                    <ul class="pl-4">
                                        <li
                                            v-for="(soal, s) in allSoals.filter(
                                                (soal) =>
                                                    soal.tipe == 'pilihan',
                                            )"
                                            class="flex gap-1 justify-between group mb-2 py-1 cursor-pointer hover:bg-sky-50"
                                            draggable="true"
                                            @dragstart="drag($event, soal)"
                                        >
                                            <span
                                                class="flex items-start gap-2"
                                            >
                                                {{ s + 1 }}.
                                                <span
                                                    v-html="soal.pertanyaan"
                                                ></span>
                                            </span>
                                            <Icon
                                                icon="mdi:plus"
                                                class="text-lg hidden group-hover:block"
                                                @click="attachSoal(soal.id)"
                                            />
                                        </li>
                                    </ul>
                                    <el-divider>
                                        <h3 class="font-bold text-sky-700">
                                            Isian
                                        </h3>
                                    </el-divider>
                                    <ul>
                                        <li
                                            v-for="(soal, s) in allSoals.filter(
                                                (soal) => soal.tipe == 'isian',
                                            )"
                                            class="flex gap-1 justify-between group mb-2 py-1 cursor-pointer hover:bg-sky-50"
                                            draggable="true"
                                            @dragstart="drag($event, soal)"
                                        >
                                            <span
                                                class="flex items-start gap-2"
                                            >
                                                {{ s + 1 }}.
                                                <span
                                                    v-html="soal.pertanyaan"
                                                ></span>
                                            </span>
                                            <Icon
                                                icon="mdi:plus"
                                                class="text-lg hidden group-hover:block"
                                                @click="attachSoal(soal.id)"
                                            />
                                        </li>
                                    </ul>
                                    <el-divider>
                                        <h3 class="font-bold text-sky-700">
                                            Uraian
                                        </h3>
                                    </el-divider>
                                    <ul>
                                        <li
                                            v-for="(soal, s) in allSoals.filter(
                                                (soal) => soal.tipe == 'uraian',
                                            )"
                                            class="flex gap-1 justify-between group mb-2 py-1 cursor-pointer hover:bg-sky-50"
                                            draggable="true"
                                            @dragstart="drag($event, soal)"
                                        >
                                            <span
                                                class="flex items-start gap-2"
                                            >
                                                {{ s + 1 }}.
                                                <span
                                                    v-html="soal.pertanyaan"
                                                ></span>
                                            </span>
                                            <Icon
                                                icon="mdi:plus"
                                                class="text-lg hidden group-hover:block"
                                                @click="attachSoal(soal.id)"
                                            />
                                        </li>
                                    </ul>
                                </el-scrollbar>
                            </div>
                        </el-card>
                    </el-affix>
                </el-col>
            </el-row>
            <el-drawer
                size="90%"
                v-model="showMiniBankSoal"
                class="hidden-md-and-up"
            >
                <el-card class="mb-4">
                    <div class="flex gap-2">
                        <el-button type="primary" @click="addSoal"
                            >Buat Soal Baru</el-button
                        >
                    </div>
                </el-card>
                <el-card>
                    <div class="content p-2">
                        <h3 class="font-black text-sky-800">
                            Bank Soal Kelas
                            {{ props.selectedAsesmen.rombel.tingkat }}
                        </h3>
                        <el-scrollbar max-height="85vh">
                            <el-divider>
                                <h3 class="font-bold text-sky-700">
                                    Pilihan Ganda
                                </h3>
                            </el-divider>
                            <ul class="pl-4">
                                <li
                                    v-for="(soal, s) in allSoals.filter(
                                        (soal) => soal.tipe == 'pilihan',
                                    )"
                                    class="flex gap-1 justify-between group mb-2 py-1 cursor-pointer hover:bg-sky-50"
                                    draggable="true"
                                    @dragstart="drag($event, soal)"
                                >
                                    <span class="flex items-start gap-2">
                                        {{ s + 1 }}.
                                        <span v-html="soal.pertanyaan"></span>
                                    </span>
                                    <Icon
                                        icon="mdi:plus"
                                        class="text-lg"
                                        @click="attachSoal(soal.id)"
                                    />
                                </li>
                            </ul>
                            <el-divider>
                                <h3 class="font-bold text-sky-700">Isian</h3>
                            </el-divider>
                            <ul>
                                <li
                                    v-for="(soal, s) in allSoals.filter(
                                        (soal) => soal.tipe == 'isian',
                                    )"
                                    class="flex gap-1 justify-between group mb-2 py-1 cursor-pointer hover:bg-sky-50"
                                    draggable="true"
                                    @dragstart="drag($event, soal)"
                                >
                                    <span class="flex items-start gap-2">
                                        {{ s + 1 }}.
                                        <span v-html="soal.pertanyaan"></span>
                                    </span>
                                    <Icon
                                        icon="mdi:plus"
                                        class="text-lg"
                                        @click="attachSoal(soal.id)"
                                    />
                                </li>
                            </ul>
                            <el-divider>
                                <h3 class="font-bold text-sky-700">Uraian</h3>
                            </el-divider>
                            <ul>
                                <li
                                    v-for="(soal, s) in allSoals.filter(
                                        (soal) => soal.tipe == 'uraian',
                                    )"
                                    class="flex gap-1 justify-between group mb-2 py-1 cursor-pointer hover:bg-sky-50"
                                    draggable="true"
                                    @dragstart="drag($event, soal)"
                                >
                                    <span
                                        class="flex items-start gap-2 w-[90%]"
                                    >
                                        {{ s + 1 }}.
                                        <span v-html="soal.pertanyaan"></span>
                                    </span>
                                    <Icon
                                        icon="mdi:plus"
                                        class="text-lg z-30"
                                        @click="attachSoal(soal.id)"
                                    />
                                </li>
                            </ul>
                        </el-scrollbar>
                    </div>
                </el-card>
            </el-drawer>
        </template>
    </el-dialog>
</template>

<style scoped>
header.el-dialog__header {
    background: rgb(249, 251, 252);
    position: sticky;
    top: 0;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
    z-index: 9999;
}
</style>
