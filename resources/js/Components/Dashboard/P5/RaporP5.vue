<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

const page = usePage();
const props = defineProps({ proyek: Object });
const emit = defineEmits(["close"]);
const loading = ref(false);
const show = ref(false);
const showTtd = ref(false);
import { cssUrl } from "@/helpers/utils";

const nilais = ref([]);
const getNilaiP5 = async () => {
    loading.value = true;
    axios
        .post(
            route("dashboard.p5.nilai.index", {
                _query: {
                    rombel: props.proyek.rombel_id,
                    proyek_id: props.proyek.id,
                },
            }),
        )
        .then((res) => {
            nilais.value = res.data.nilais;
            loading.value = false;
        })
        .catch((err) => console.log(err));
};

const selectedNilai = ref([]);
const selectRow = (val) => {
    selectedNilai.value = val;
};

const cetak = async () => {
    const element = document.querySelector(".cetak");
    // const cssUrl = page.props.app_env == 'local' ? 'https://localhost:5173/resources/css/app.css' : '/assets/css/app.css'
    let win = window.open("", "_blank", "width=800,height=1024");
    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Rapor P5: ${props.proyek.nama}</title>
                <link rel="stylesheet" href="${cssUrl()}" />
            </head>
            <body>
                ${element.outerHTML}
            </body>
        </html>
    `;
    win.document.write(html);
    setTimeout(() => {
        win.print();
        win.close();
    }, 1000);
};

onBeforeMount(() => {
    getNilaiP5();
    show.value = props.value !== null;
});
</script>

<template>
    <el-dialog v-model="show" fullscreen :show-close="false">
        <template #header>
            <div class="flex items-center justify-between z-40">
                <h3>Rapor P5</h3>
                <el-button circle type="danger" @click="emit('close')">
                    <Icon icon="mdi:close" />
                </el-button>
            </div>
        </template>
        <template #default>
            <el-row :gutter="20">
                <el-col :span="16">
                    <div
                        class="cetak bg-slate-300 p-4 print:p-0 print:bg-white min-h-[80vh] z-30"
                        :class="
                            selectedNilai.length < 1
                                ? 'flex items-center justify-center'
                                : ''
                        "
                    >
                        <h3
                            class="text-xl font-bold text-sky-800"
                            v-if="selectedNilai.length < 1"
                        >
                            Pilih Siswa di samping
                        </h3>

                        <template v-for="(nilai, n) in selectedNilai" :key="n">
                            <div
                                class="page h-[330mm] print:h-[330mm] w-[216mm] bg-white mx-auto break-after-page shadow-md print:shadow-none my-6 cover p-6 relative overflow-hidden"
                            >
                                <img
                                    src="/img/corner-tr-1.svg"
                                    alt="Ornamen-TR"
                                    class="absolute w-[50%] -right-28 -top-12"
                                />
                                <img
                                    src="/img/corner-bl-1.svg"
                                    alt="Ornamen-TR"
                                    class="absolute w-[50%] -left-16 -bottom-10"
                                />
                                <div
                                    class="content h-[95%] border-4 border-black border-dashed flex flex-col justify-center gap-8 m-8"
                                >
                                    <img
                                        src="/img/tutwuri.png"
                                        width="100"
                                        class="mx-auto"
                                    />
                                    <h3
                                        class="uppercase font-black text-center text-black mb-20 text-xl"
                                    >
                                        RAPOR <br />
                                        PROYEK PENGUATAN PROFIL PELAJAR
                                        PANCASILA <br />
                                        {{ props.proyek.rombel.sekolah.nama }}
                                    </h3>
                                    <el-image
                                        :src="page.props.sekolahs[0].logo"
                                        style="width: 200px; margin: 0 auto"
                                    >
                                        <template #error>
                                            <img
                                                src="https://pusatinformasi.kolaborasi.kemdikbud.go.id/hc/article_attachments/30787241285145"
                                                width="200"
                                                class="mx-auto"
                                            />
                                        </template>
                                    </el-image>
                                    <div class="py-20">
                                        <p class="text-center text-lg">
                                            Nama Peserta Didik
                                        </p>
                                        <h3
                                            class="p-1 border border-black w-[50%] mx-auto text-center text-xl uppercase font-bold"
                                        >
                                            {{ nilai.nama }}
                                        </h3>
                                        <p class="text-center text-lg">NISN</p>
                                        <h3
                                            class="p-1 border border-black w-[50%] mx-auto text-center text-xl uppercase font-bold"
                                        >
                                            {{ nilai.siswa_id }}
                                        </h3>
                                    </div>
                                    <h3
                                        class="text-center mt-24 text-xl font-black text-black font-[arial]"
                                    >
                                        TA. {{ page.props.periode.tapel.label }}
                                    </h3>
                                </div>
                            </div>
                            <div
                                class="page w-[216mm] bg-white mx-auto break-after-page shadow-md print:shadow-none my-6 proyek p-4 relative"
                            >
                                <div class="content h-full text-black">
                                    <h3
                                        class="font-bold text-lg text-center mb-4 uppercase"
                                    >
                                        Rapor Proyek Penguatan Profil Pelajar
                                        pancasila
                                    </h3>
                                    <table
                                        class="w-full border-b border-double border-10 border-black"
                                    >
                                        <tbody>
                                            <tr>
                                                <td>Nama Sekolah</td>
                                                <td>:</td>
                                                <td>
                                                    {{
                                                        props.proyek.rombel
                                                            .sekolah.nama
                                                    }}
                                                </td>
                                                <td>Kelas</td>
                                                <td>:</td>
                                                <td>
                                                    {{
                                                        props.proyek.rombel
                                                            .label
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Alamat Sekolah</td>
                                                <td>:</td>
                                                <td>
                                                    {{
                                                        props.proyek.rombel
                                                            .sekolah.alamat
                                                    }},
                                                    {{
                                                        props.proyek.rombel
                                                            .sekolah.desa
                                                    }}
                                                </td>
                                                <td>Fase</td>
                                                <td>:</td>
                                                <td>
                                                    {{
                                                        props.proyek.rombel.fase
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nama Siswa</td>
                                                <td>:</td>
                                                <td>{{ nilai.nama }}</td>
                                                <td>Tahun Pelajaran</td>
                                                <td>:</td>
                                                <td>
                                                    {{
                                                        props.proyek.tapel.label
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>NISN</td>
                                                <td>:</td>
                                                <td>{{ nilai.siswa_id }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <h3 class="judul-proyek mt-8">
                                        Proyek: {{ props.proyek.id }} |
                                        {{ props.proyek.nama }}
                                    </h3>
                                    <article
                                        class="border border-black p-2 text-justify"
                                    >
                                        {{ props.proyek.deskripsi }}
                                    </article>

                                    <h3 class="mt-8 text-lg mb-2">
                                        Keterangan:
                                    </h3>
                                    <table class="border">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="border border-black p-2"
                                                >
                                                    BB (Belum Berkembang)
                                                </th>
                                                <th
                                                    class="border border-black p-2"
                                                >
                                                    MB (Mulai Berkembang)
                                                </th>
                                                <th
                                                    class="border border-black p-2"
                                                >
                                                    BSH (Berkembang Sesuai
                                                    Harapan)
                                                </th>
                                                <th
                                                    class="border border-black p-2"
                                                >
                                                    SB (Sangat Berkembang)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td
                                                    class="p-2 border border-black"
                                                >
                                                    Siswa masih membutuhkan
                                                    bimbingan dalam
                                                    mengembangkan kemampuan
                                                </td>
                                                <td
                                                    class="p-2 border border-black"
                                                >
                                                    Siswa mulai mengembangkan
                                                    kemampuan namun masih belum
                                                    ajeg
                                                </td>
                                                <td
                                                    class="p-2 border border-black"
                                                >
                                                    Siswa telah mengembangkan
                                                    kemampuan hingga berada
                                                    dalam tahap ajeg
                                                </td>
                                                <td
                                                    class="p-2 border border-black"
                                                >
                                                    Siswa mengembangkan
                                                    kemampuannya melampaui
                                                    harapan
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <el-divider></el-divider>
                                </div>
                            </div>
                            <div
                                class="page w-[216mm] bg-white mx-auto break-after-page shadow-md print:shadow-none my-6 nilai p-8 text-black"
                            >
                                <table class="w-full border border-black">
                                    <thead>
                                        <tr>
                                            <th
                                                class="border border-black uppercase"
                                            >
                                                {{ props.proyek.nama }}
                                            </th>
                                            <th class="border border-black">
                                                BB
                                            </th>
                                            <th class="border border-black">
                                                MB
                                            </th>
                                            <th class="border border-black">
                                                BSH
                                            </th>
                                            <th class="border border-black">
                                                SB
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template
                                            v-for="(nil, n) in nilai.nilais"
                                        >
                                            <tr>
                                                <td
                                                    colspan="5"
                                                    class="border border-black bg-slate-100 p-2 font-black"
                                                >
                                                    {{
                                                        nil.apd.elemen.dimensi
                                                            .dimensi
                                                    }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    class="border border-black p-2"
                                                >
                                                    <ul class="list-disc pl-6">
                                                        <li>
                                                            {{
                                                                nil.apd.elemen
                                                                    .teks
                                                            }}
                                                        </li>
                                                    </ul>
                                                    <p class="pl-6">
                                                        {{ nil.apd.teks }}
                                                    </p>
                                                </td>
                                                <td
                                                    class="border border-black px-2 w-14 text-center"
                                                >
                                                    <div
                                                        v-if="nil.skor == 'BB'"
                                                    >
                                                        {{ nil.skor }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="border border-black px-2 w-14 text-center"
                                                >
                                                    <div
                                                        v-if="nil.skor == 'MB'"
                                                    >
                                                        {{ nil.skor }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="border border-black px-2 w-14 text-center"
                                                >
                                                    <div
                                                        v-if="nil.skor == 'BSH'"
                                                    >
                                                        {{ nil.skor }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="border border-black px-2 w-14 text-center"
                                                >
                                                    <div
                                                        v-if="nil.skor == 'SB'"
                                                    >
                                                        {{ nil.skor }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                                <h3 class="font-bold">Catatan proses:</h3>
                                <p class="border border-black p-2 text-justify">
                                    Ananda
                                    <span class="capitalize fotn-bold">{{
                                        nilai.nama
                                    }}</span>
                                    {{ nilai.proses }}
                                </p>

                                <div class="grid grid-cols-3 gap-8 mt-16">
                                    <div class="relative">
                                        <p>Mengetahui:</p>
                                        <p>Orang Tua / Wali,</p>

                                        <p
                                            class="mt-14 underline font-bold uppercase border-b border-black border-dotted"
                                        >
                                            &nbsp;
                                        </p>
                                    </div>
                                    <div class="relative">
                                        <p>&nbsp;</p>
                                        <p>Kepala Sekolah,</p>

                                        <img
                                            v-if="showTtd"
                                            src="/storage/images/ttd/196804222005011004.png"
                                            alt="TTD Kepsek"
                                            class="absolute w-[100px] translate-x-[15px]"
                                        />

                                        <p
                                            class="mt-14 underline font-bold uppercase"
                                        >
                                            {{
                                                page.props.sekolahs[0].ks.nama
                                            }},
                                            {{
                                                page.props.sekolahs[0].ks
                                                    .gelar_belakang
                                            }}
                                        </p>
                                        <p>
                                            NIP.
                                            {{ page.props.sekolahs[0].ks.nip }}
                                        </p>
                                    </div>
                                    <div class="relative">
                                        <p
                                            contenteditable="true"
                                            class="bg-yellow-100 print:bg-white"
                                        >
                                            Malang, 17 Agustus 2024
                                        </p>
                                        <p>Guru Kelas,</p>

                                        <p
                                            class="mt-14 underline font-bold uppercase"
                                        >
                                            {{
                                                page.props.auth.user.userable
                                                    .nama
                                            }},
                                            {{
                                                page.props.auth.user.userable
                                                    .gelar_belakang
                                            }}
                                        </p>
                                        <p
                                            v-if="
                                                page.props.auth.user.userable
                                                    .status !== 'gtt'
                                            "
                                        >
                                            NIP.
                                            {{
                                                page.props.auth.user.userable
                                                    .nip
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </el-col>
                <el-col :span="8">
                    <div class="list">
                        <el-affix :offset="50">
                            <div
                                class="toolbox flex items-center justify-between"
                                v-if="selectedNilai.length > 0"
                            >
                                <el-checkbox v-model="showTtd"
                                    >Tampilkan TTD</el-checkbox
                                >
                                <el-button
                                    size="large"
                                    type="primary"
                                    @click="cetak"
                                    >Cetak</el-button
                                >
                            </div>
                            <h4 class="text-lg text-sky-700 font-bold mt-8">
                                Data Siswa
                            </h4>
                            <el-table
                                :data="nilais"
                                @selection-change="selectRow"
                            >
                                <el-table-column
                                    label="#"
                                    type="selection"
                                ></el-table-column>
                                <!-- <el-table-column label="#" type="index"></el-table-column> -->
                                <el-table-column
                                    label="NISN"
                                    prop="siswa_id"
                                ></el-table-column>
                                <el-table-column
                                    label="Nama"
                                    prop="nama"
                                ></el-table-column>
                            </el-table>
                        </el-affix>
                    </div>
                </el-col>
            </el-row>
        </template>
    </el-dialog>
</template>
