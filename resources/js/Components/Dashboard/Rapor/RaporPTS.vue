<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { capitalize } from "@/helpers/String";
import dayjs from "dayjs";
import "dayjs/locale/id";
import axios from "axios";
import { cssUrl } from "@/helpers/utils";

const loading = ref(false);
const page = usePage();
const props = defineProps({ siswa: Object, rombel: Object });

const Kop = defineAsyncComponent(
    () => import("@/Components/Dashboard/Kop.vue"),
);
const emit = defineEmits(["close", "nextSiswa", "prevSiswa"]);
const periode = computed(() => {});

const sekolah = computed(() => page.props.sekolahs[0]);
const nilais = ref([]);
const close = () => {
    emit("close");
};

const showNext = async () => {
    emit("nextSiswa");
    getNilaiPTS();
};
const showPrev = async () => {
    emit("prevSiswa");
    getNilaiPTS();
};
const cetak = async () => {
    let el = document.querySelector(".cetak");
    let html = `<!doctype html>
				<html>
					<head>
						<title>Cover Rapor</title>
						<link rel="stylesheet" href="${cssUrl()}" />
					</head>
					<body>
						${el.outerHTML}
					</body>

				</html>
	`;
    let win = window.open(
        page.props.appUrl + "/print",
        "_blank",
        "height=600,width=1500",
    );
    win.document.write(html);
    setTimeout(() => {
        win.print();
        win.close();
    }, 1500);
};

const getNilaiPTS = async () => {
    loading.value = true;
    await axios
        .post(
            route("dashboard.rapor.pts", {
                _query: {
                    rombelId: props.rombel.kode,
                    semester:
                        route().params.semester ??
                        page.props.periode.semester.kode,
                    tapel: page.props.periode.tapel.kode,
                    siswaId: props.siswa.nisn,
                    sekolahId: sekolah.value.npsn,
                },
            }),
        )
        .then((res) => {
            // console.log(res);
            nilais.value = res.data;
            setTimeout(() => {
                loading.value = false;
            }, 1000);
        })
        .catch((err) => {
            console.log(err);
        });
};

onBeforeMount(async () => {
    getNilaiPTS();
});
</script>

<template>
    <Head
        :title="`Laporan Hasil Belajar Tengah Semester ${route().params.semester ?? page.props.periode.semester.label}`"
    />
    <div
        class="toolbar h-12 bg-slate-200 w-full flex items-center justify-between print:hidden px-4"
    >
        <span>
            Cetak Rapor PTS
            {{
                route().params.semester ?? page.props.periode.semester.label
            }}
            Tahun
            {{ page.props.periode.tapel.label }}
        </span>
        <div class="toolbar-items flex items-center">
            <el-button-group>
                <el-button>
                    <Icon icon="mdi:chevron-double-left" @click="showPrev" />
                </el-button>
                <el-button @click="showNext">
                    <Icon icon="mdi:chevron-double-right" />
                </el-button>
            </el-button-group>
            <el-button @click="cetak">
                <Icon icon="mdi:printer" />
            </el-button>
            <el-button circle type="danger" @click="close">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </div>
    <div
        class="cetak bg-slate-100 print:bg-white w-full bg-cover p-4 print:p-0 text-center font-serif"
    >
        <div
            class="page w-[80%] print:w-full bg-white mx-auto shadow-lg print:shadow-none"
        >
            <Kop />
            <div class="meta my-6">
                <h3 class="text-center font-bold text-xl uppercase">
                    Laporan Hasil Sumatif Tengah Semester (STS)
                </h3>
                <div class="grid grid-cols-6 text-left px-8 my-8">
                    <div class="col-span-3">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Nama Peserta Didik</td>
                                    <td class="px-1">:</td>
                                    <td>{{ siswa.nama }}</td>
                                </tr>
                                <tr>
                                    <td>NISN / NIS</td>
                                    <td class="px-1">:</td>
                                    <td>{{ siswa.nisn }} / {{ siswa.nis }}</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td class="px-1">:</td>
                                    <td>{{ rombel.label }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div></div>
                    <div>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Semester</td>
                                    <td class="px-1">:</td>
                                    <td>
                                        {{ nilais["semester"]?.label }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tahun Ajaran</td>
                                    <td class="px-1">:</td>
                                    <td>
                                        {{ nilais["tapel"]?.label }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="table px-8 w-full">
                <table class="border" width="100%">
                    <thead>
                        <tr class="bg-slate-100">
                            <th
                                class="border border-black font-bold uppercase p-2"
                            >
                                No
                            </th>
                            <th
                                class="border border-black font-bold uppercase p-2"
                            >
                                Mata Pelajaran
                            </th>
                            <th
                                class="border border-black font-bold uppercase p-2"
                            >
                                Nilai STS
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="(nilai, m) in nilais.pts"
                            :key="nilai.id"
                        >
                            <tr>
                                <td class="border border-black p-2">
                                    {{ m + 1 }}
                                </td>
                                <td class="border border-black p-2 text-left">
                                    {{ nilai.mapel?.label }}
                                </td>
                                <td class="border border-black p-2">
                                    {{ nilai.skor }}
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="ttd grid grid-cols-7 mt-24">
                <div class="col-span-3">
                    <p>&nbsp;</p>
                    <p>Orang tua / Wali Murid</p>

                    <p
                        class="font-bold uppercase underline leading-4 mt-20 border-dotted border-b-2 border-black w-[80%] mx-auto"
                    >
                        &nbsp;
                    </p>
                    <p class="leading-4 mb-8"></p>
                </div>
                <div class="col-span-1"></div>
                <div class="col-span-3">
                    <p>
                        {{ capitalize(sekolah.desa) }},
                        {{
                            dayjs(nilais.tanggal)
                                .locale("id")
                                .format("DD MMMM YYYY")
                        }}
                    </p>
                    <p>Wali Kelas {{ rombel.label }}</p>

                    <p class="font-bold underline leading-4 mt-20">
                        <span class="uppercase">{{
                            rombel.wali_kelas.nama
                        }}</span
                        >, {{ rombel.wali_kelas.gelar_belakang }}
                    </p>
                    <p
                        class="leading-4 mb-8"
                        v-if="rombel.wali_kelas.status !== 'gtt'"
                    >
                        NIP. {{ rombel.wali_kelas.nip }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <Teleport to="body">
        <div
            class="z-[999999] backdrop-blur fixed top-0 right-0 bottom-0 left-0 bg-slate-700 bg-opacity-30 flex items-center justify-center"
            v-if="loading"
        >
            <Icon icon="mdi:loading" class="animate-spin text-8xl text-white" />
        </div>
    </Teleport>
</template>
