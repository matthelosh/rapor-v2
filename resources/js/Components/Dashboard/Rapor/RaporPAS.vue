<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { capitalize } from '@/helpers/String';
import dayjs from 'dayjs';
import 'dayjs/locale/id'
import axios from 'axios';

const page = usePage()
const props = defineProps({siswa: Object, rombel: Object})

const emit = defineEmits(['close', 'nextSiswa', 'prevSiswa'])

const sekolah = computed(() => page.props.sekolahs[0])
const nilais = ref([])
const close = () => {
    emit('close')
}

const showNext = () => {
    emit('nextSiswa')
    getNilaiPAS()
}
const showPrev = () => {
    emit('prevSiswa')
    getNilaiPAS()
}
const cetak = async() => {
    let host = window.location.host
	let el = document.querySelector(".cetak")
	let cssUrl = page.props.app_env == 'local' ? 'http://localhost:5173/resources/css/app.css' : `/build/assets/app.css`
	let html = `<!doctype html>
				<html>
					<head>
						<title>Cover Rapor</title>
						<link rel="stylesheet" href="${cssUrl}" />
					</head>
					<body>
						${el.outerHTML}
					</body>

				</html>
	`
	let win = window.open(host+'/print',"_blank","height=600,width=1500")
	await win.document.write(html)
    setTimeout(() => {
        win.print();
        // win.close();
    }, 1500);

}

const getNilaiPAS = async() => {
    await axios.post(route('dashboard.rapor.pas', {
        _query: {
            rombelId: props.rombel.kode,
            semester: page.props.periode.semester.kode,
            tapel: page.props.periode.tapel.kode,
            siswaId: props.siswa.nisn,
            sekolahId: sekolah.value.npsn
        }
    })).then(res => {
        console.log(res)
        nilais.value = res.data
    }).catch(err => {
        console.log(err)
    })
}

onBeforeMount(async() => {
    getNilaiPAS()
})
</script>

<template>
    <Head :title="`Laporan Hasil Belajar Akhir Semester ${page.props.periode.semester.label}`" />
    <div class="toolbar h-12 bg-slate-200 w-full flex items-center justify-between print:hidden px-4">
        <span>
            Cetak Rapor PAS {{ page.props.periode.semester.label }} Tahun {{ page.props.periode.tapel.label }}
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
    <el-scrollbar height="88vh">
        <div class="cetak bg-slate-100 print:bg-white w-full bg-cover pt-4 pb-10 text-center font-serif">
            <div class="page w-[60%] print:w-full bg-white mx-auto shadow-lg print:shadow-none pb-6 pt-4 print:pt-0">
                <div class="meta my-6">
                    <h3 class="text-center font-bold text-xl uppercase ">Laporan Hasil Belajar</h3>
                    <div class="grid grid-cols-6 text-left px-8 my-8">
                        <div class="col-span-3">
                            <table >
                                <tr>
                                    <td>Nama Peserta Didik</td>
                                    <td class="px-1">:</td>
                                    <td>{{ siswa.nama }}</td>
                                </tr>
                                <tr>
                                    <td>NISN</td>
                                    <td class="px-1">:</td>
                                    <td>{{ siswa.nisn }}</td>
                                </tr>
                                <tr>
                                    <td>Sekolah</td>
                                    <td class="px-1">:</td>
                                    <td>{{ sekolah.nama }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td class="px-1">:</td>
                                    <td>{{ capitalize(sekolah.alamat) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div></div>
                        <div class="col-span-2">
                            <table >
                                <tr>
                                    <td>Kelas</td>
                                    <td class="px-1">:</td>
                                    <td>{{ props.rombel.label }}</td>
                                </tr>
                                <tr>
                                    <td>Fase</td>
                                    <td class="px-1">:</td>
                                    <td>{{ props.rombel.fase }}</td>
                                </tr>
                                <tr>
                                    <td>Semester</td>
                                    <td class="px-1">:</td>
                                    <td>{{ page.props.periode.semester.label }}</td>
                                </tr>
                                <tr>
                                    <td>Tahun Pelajaran</td>
                                    <td class="px-1">:</td>
                                    <td>{{ page.props.periode.tapel.label }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="nilai-mapel px-8  w-full">
                    <table class="border" width="100%">
                        <thead>
                            <tr class="bg-slate-100">
                                <th class="border border-black font-bold uppercase p-2">No</th>
                                <th class="border border-black font-bold uppercase p-2">Mata Pelajaran</th>
                                <th class="border border-black font-bold uppercase p-2">Nilai Akhir</th>
                                <th class="border border-black font-bold uppercase p-2">Capaian Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(mapel, m) in page.props.sekolahs[0].mapels" :key="m">
                                <tr>
                                    <td class="border border-black px-2 w-[70px]">{{ m+1 }}</td>
                                    <td class="border border-black px-2 text-left w-[400px]">{{ mapel.label }}</td>
                                    <td class="border border-black px-2 w-[100px]">90</td>
                                    <td class="border border-black px-2"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="nilai-ekskul px-8 w-full my-8">
                    <table class="border" width="100%">
                        <thead>
                            <tr>
                                <th class="border border-black p-2 bg-slate-100 w-[70px]">No</th>
                                <th class="border border-black p-2 bg-slate-100 w-[400px]">Ekstrakurikuler</th>
                                <th class="border border-black p-2 bg-slate-100">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(ekskul, e) in page.props.sekolahs[0].ekskuls" :key="ekskul.id">
                                <tr>
                                    <td class="border border-black px-2">{{ e+1 }}</td>
                                    <td class="border border-black px-2 text-left">{{ ekskul.nama }}</td>
                                    <td class="border border-black px-2 text-left">Keterangan</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="ttd grid grid-cols-3 mt-8 px-8">
                    <div class="col-span-1">
                        <table>
                            <thead>
                                <tr class="bg-slate-100">
                                    <th class="border" colspan="2">Ketidakhadiran</th>
                                </tr>
                            </thead>
                            <tbody class="text-left">
                                <tr>
                                    <td class="px-2 border">Sakit</td>
                                    <td class="px-2 border">.... hari</td>
                                </tr>
                                <tr>
                                    <td class="px-2 border">Izin</td>
                                    <td class="px-2 border">.... hari</td>
                                </tr>
                                <tr>
                                    <td class="px-2 border">Tanpa Keterangan</td>
                                    <td class="px-2 border">.... hari</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-left">
                        <div v-if="(parseInt(page.props.periode.semester.kode) / 2 === 0)">
                            <p class="font-bold">Keputusan:</p>
                            <p>Berdasarkan hasil belajar yang telah dicapai,</p>
                            <p>Ananda {{ props.siswa.nama }}, dinyatakan Naik ke kelas {{ parseInt(props.rombel.tingkat) + 1 }}</p>
                        </div>
                    </div>
                    <div>
                        <p>{{ capitalize(sekolah.desa) }}, {{ dayjs(new Date()).locale('id').format('DD MMMM YYYY') }}</p>
                        <p>Wali Kelas {{ rombel.label }}</p>

                        <p class="font-bold uppercase underline leading-4 mt-20">{{ page.props.auth.user.userable.nama }}, {{ page.props.auth.user.userable.gelar_belakang }}</p>
                        <p class="leading-4">NIP. {{ page.props.auth.user.userable.nip }}</p>
                    </div>
                    <div>
                        <p>&nbsp;</p>
                        <p>TTD Orang Tua Peserta Didik</p>
                        <p class="font-bold uppercase underline leading-4 mt-20">.......................................</p>
                        <p class="leading-4"></p>
                    </div>
                    <div>
                        <p class="mt-20">Mengetahui,</p>
                        <p>Kepala {{ capitalize(page.props.sekolahs[0].nama) }}</p>
                        <p class="font-bold uppercase underline leading-4 mt-20">{{ page.props.sekolahs[0].ks.nama }}, {{ page.props.sekolahs[0].ks.gelar_belakang }}</p>
                        <p class="leading-4">NIP. {{ page.props.sekolahs[0].ks.nip }}</p>
                    </div>
                    <div></div>
                </div>
            </div>
        </div>
    </el-scrollbar>
</template>