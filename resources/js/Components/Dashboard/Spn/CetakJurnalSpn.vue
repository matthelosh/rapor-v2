<script setup>
import { Icon } from "@iconify/vue";
import { usePage, router } from "@inertiajs/vue3";
import { defineAsyncComponent, onBeforeMount, ref } from "vue";
const Kop = defineAsyncComponent(
    () => import("@/Components/Dashboard/Kop.vue"),
);
const page = usePage();
const props = defineProps({ jilid: Object });
const emit = defineEmits(["close"]);
const show = ref(false);
import dayjs from "dayjs";
import "dayjs/locale/id";
dayjs.locale("id");

const savedJurnals = ref([]);
const jurnals = ref([]);
const addJurnal = (index) => {
    const jurnal = savedJurnals.value[index];
    jurnals.value.push(jurnal);
    savedJurnals.value.splice(index, 1);
};

const cetak = () => {
    const el = document.querySelector(".cetak");
    let cssUrl =
        page.props.app_env == "local"
            ? "https://localhost:5173/resources/css/app.css"
            : `/build/assets/app.css`;
    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Jurnal SPN</title>
                <link rel="stylesheet" href="${cssUrl}" />
            </head>
            <body>
                ${el.outerHTML}
            </body>
        </html>

    `;
    let win = window.open("", "_blank", "width=800,height=1200");
    win.document.write(html);

    setTimeout(() => {
        win.print();
        // win.close()
    }, 1000);
};

const unduhPdf = () => {
    const element = document.querySelector(".cetak");
    // const optios = {
    //     filename: "Testing html2pdf",
    //     margin: 0,
    //     image: { type: "jpeg", quality: 0.98 },
    //     html2canvas: { scale: 2 },
    //     jsPDF: {
    //         unit: "mm",
    //         format: "a4",
    //         orientation: "portrait",
    //     },
    // };

    // html2pdf().from(element).set(optios).save();
};

const removeJurnal = (index) => {
    const jurnal = jurnals.value[index];
    savedJurnals.value.push(jurnal);
    jurnals.value.splice(index, 1);
};
onBeforeMount(() => {
    show.value = props.jilid !== null;
    savedJurnals.value = props.jilid.jurnals;
});
</script>

<template>
    <el-dialog fullscreen v-model="show" :show-close="false">
        <template #header>
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-lg text-sky-800">
                    Cetak Jurnal SPN {{ props.jilid.nama }}
                </h3>
                <el-button circle type="danger" @click="emit('close')">
                    <Icon icon="mdi:close" />
                </el-button>
            </div>
        </template>
        <el-card shadow="never">
            <el-row :gutter="20">
                <el-col :span="18">
                    <div class="cetak">
                        <el-card class="mb-4">
                            <Kop />
                            <h3
                                class="text-center font-black uppercase my-8 text-lg"
                            >
                                Laporan Berkala Sekolah Plus Ngaji
                            </h3>
                            <table class="mx-auto my-6">
                                <tr>
                                    <td>Nama Sekolah</td>
                                    <td class="px-2">:</td>
                                    <td>{{ page.props.sekolahs[0].nama }}</td>
                                    <td class="px-4"></td>
                                    <td>Jilid</td>
                                    <td class="px-2">:</td>
                                    <td>{{ props.jilid.nama }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Guru</td>
                                    <td class="px-2">:</td>
                                    <td>
                                        {{ page.props.auth.user.userable.nama }}
                                    </td>
                                    <td class="px-4"></td>
                                    <td>Semester</td>
                                    <td class="px-2">:</td>
                                    <td>
                                        {{ page.props.periode.semester.label }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td class="px-2">:</td>
                                    <td>
                                        {{ page.props.auth.user.userable.nip }}
                                    </td>
                                    <td class="px-4"></td>
                                    <td>Tahun Pelajaran</td>
                                    <td class="px-2">:</td>
                                    <td>
                                        {{ page.props.periode.tapel.label }}
                                    </td>
                                </tr>
                            </table>

                            <div class="jurnals">
                                <el-alert
                                    v-if="jurnals.length < 1"
                                    type="error"
                                    style="
                                        width: 50%;
                                        margin: 20px auto;
                                        padding: 20px;
                                    "
                                    title="Pilih jurnal di samping"
                                    :show-close="false"
                                >
                                </el-alert>
                                <div class="items" v-else>
                                    <template v-for="(jurnal, j) in jurnals">
                                        <el-card
                                            class="mb-10 w-[80%] mx-auto group relative break-inside-avoid-page"
                                        >
                                            <Icon
                                                icon="mdi:trash-can"
                                                class="hidden group-hover:block text-red-500 text-2xl hover:cursor-pointer absolute right-4"
                                                @click="removeJurnal(j)"
                                            />
                                            <table
                                                class="w-full border border-black"
                                            >
                                                <tr class="bg-slate-100">
                                                    <td
                                                        class="p-2 border border-black"
                                                    >
                                                        <h3
                                                            class="font-bold uppercase text-lg"
                                                        >
                                                            Materi
                                                        </h3>
                                                    </td>
                                                    <td
                                                        class="border border-black"
                                                        align="center"
                                                    >
                                                        :
                                                    </td>
                                                    <td
                                                        class="p-2 border border-black"
                                                    >
                                                        <h3
                                                            class="font-bold uppercase text-lg"
                                                        >
                                                            {{ jurnal.materi }}
                                                        </h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        class="p-2 border border-black"
                                                    >
                                                        <h3>Tanggal</h3>
                                                    </td>
                                                    <td
                                                        class="border border-black text-center"
                                                    >
                                                        <h3>:</h3>
                                                    </td>
                                                    <td
                                                        class="p-2 border border-black"
                                                    >
                                                        <h3
                                                            contenteditable="true"
                                                        >
                                                            {{
                                                                dayjs(
                                                                    jurnal.created_at,
                                                                ).format(
                                                                    "DD MMMM YYYY",
                                                                )
                                                            }}
                                                        </h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        class="p-2 border border-black"
                                                    >
                                                        <h3>Keterangan</h3>
                                                    </td>
                                                    <td
                                                        class="border border-black"
                                                        align="center"
                                                    >
                                                        <h3>:</h3>
                                                    </td>
                                                    <td
                                                        class="p-2 border border-black"
                                                    >
                                                        <p>
                                                            {{
                                                                jurnal.keterangan
                                                            }}
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        class="p-2 border border-black"
                                                    >
                                                        <h3>Absensi</h3>
                                                    </td>
                                                    <td
                                                        class="border border-black"
                                                        align="center"
                                                    >
                                                        <h3>:</h3>
                                                    </td>
                                                    <td
                                                        class="p-2 border border-black"
                                                    >
                                                        <ul
                                                            class="list-decimal pl-4"
                                                            v-if="
                                                                jurnal.absensis
                                                            "
                                                        >
                                                            <template
                                                                v-for="nisn in jurnal.absensis.split(
                                                                    ',',
                                                                )"
                                                            >
                                                                <li>
                                                                    {{
                                                                        jilid.siswas.find(
                                                                            (
                                                                                siswa,
                                                                            ) =>
                                                                                siswa.nisn ==
                                                                                nisn,
                                                                        ).nama
                                                                    }}
                                                                    ({{
                                                                        jilid.siswas.find(
                                                                            (
                                                                                siswa,
                                                                            ) =>
                                                                                siswa.nisn ==
                                                                                nisn,
                                                                        )
                                                                            .rombels[0]
                                                                            .label
                                                                    }})
                                                                </li>
                                                            </template>
                                                        </ul>
                                                        <p v-else>Nihil</p>
                                                        <!-- {{jurnal.absensis.split(',')}} -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        class="text-center p-2 border border-black"
                                                        colspan="3"
                                                        align="center"
                                                    >
                                                        <h3 class="text-center">
                                                            Bukti Foto:
                                                        </h3>
                                                        <div
                                                            :class="`grid grid-cols-${
                                                                jurnal.fotos.split(
                                                                    ',',
                                                                ).length
                                                            }`"
                                                            class="gap-4 p-2"
                                                        >
                                                            <template
                                                                v-for="foto in jurnal.fotos.split(
                                                                    ',',
                                                                )"
                                                            >
                                                                <el-image
                                                                    :src="foto"
                                                                    fit="cover"
                                                                ></el-image>
                                                            </template>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </el-card>
                                    </template>
                                </div>
                            </div>
                            <div class="ttd grid grid-cols-10">
                                <div
                                    class="col-span-4 ttd-ks text-center relative"
                                >
                                    <p>Mengetahui,</p>
                                    <p>
                                        Kepala
                                        {{
                                            page.props.auth.user.userable
                                                .sekolahs[0]?.nama
                                        }}
                                    </p>
                                    <img
                                        :src="`/storage/images/ttd/${page.props.sekolahs[0]?.ks?.nip}.png`"
                                        alt="TTD KS"
                                        class="absolute ml-[50%] -translate-x-[50%] -translate-y-3"
                                    />
                                    <p class="mt-16 font-bold underline">
                                        <span class="uppercase">{{
                                            page.props.sekolahs[0]?.ks?.nama
                                        }}</span
                                        >,
                                        {{
                                            page.props.sekolahs[0]?.ks
                                                ?.gelar_belakang
                                        }}
                                    </p>
                                    <p class="leading-4">
                                        NIP.
                                        {{ page.props.sekolahs[0]?.ks?.nip }}
                                    </p>
                                </div>
                                <div class="t col-span-2"></div>
                                <div class="col-span-4 ttd-gpai text-center">
                                    <p>
                                        Malang,
                                        {{
                                            dayjs(new Date()).format(
                                                "DD MMMM YYYY",
                                            )
                                        }}
                                    </p>
                                    <p>Guru Pendidikan Agama Islam</p>
                                    <p class="mt-16 font-bold underline">
                                        <span class="uppercase">{{
                                            page.props.auth.user.userable.nama
                                        }}</span
                                        >,
                                        {{
                                            page.props.auth.user.userable
                                                .gelar_belakang
                                        }}
                                    </p>
                                    <p class="leading-4">
                                        NIP.
                                        {{ page.props.auth.user.userable.nip }}
                                    </p>
                                </div>
                            </div>
                        </el-card>
                    </div>
                </el-col>
                <el-col :span="6">
                    <el-card>
                        <div class="flex items-center mb-4">
                            <el-button type="primary" @click="cetak">
                                <Icon icon="mdi:printer" />
                                <span class="ml-1">Cetak</span>
                            </el-button>
                        </div>
                        <h3 class="font-bold uppercase text-sky-700 mb-2">
                            Data Jurnal {{ props.jilid.nama }}:
                        </h3>
                        <ul>
                            <li
                                v-for="(jurnal, j) in savedJurnals"
                                class="mb-2 flex items-center gap-2 border-b border-dashed hover:bg-sky-50 py-1"
                            >
                                <el-button
                                    circle
                                    size="small"
                                    type="primary"
                                    @click="addJurnal(j)"
                                >
                                    <Icon icon="mdi:plus" />
                                </el-button>
                                {{
                                    dayjs(jurnal.created_at).format(
                                        "DD/MM/YYYY",
                                    )
                                }}
                                | {{ jurnal.materi }}
                            </li>
                        </ul>
                    </el-card>
                </el-col>
            </el-row>
        </el-card>
    </el-dialog>
</template>

<style>
.el-dialog__body {
    padding-top: 0px !important;
}
</style>
