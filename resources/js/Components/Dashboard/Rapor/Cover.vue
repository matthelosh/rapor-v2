<script setup>
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

const page = usePage()
const props = defineProps({siswa: Object})
const emit = defineEmits(['close', 'nextSiswa', 'prevSiswa'])

const sekolah = computed(() => page.props.sekolahs[0])
const logo = computed(() => sekolah.value.logo ?? '/img/tutwuri.png')
const defaultLogo = (e) => {
    e.target.src = '/img/tutwuri.png'
    e.onerror = null
}
const close = () => {
    emit('close')
}

const showNext = () => {
    emit('nextSiswa')
}
const showPrev = () => {
    emit('prevSiswa')
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
	let win = window.open(host+'/print',"_blank","height=600,width=1024")
	await win.document.write(html)
    setTimeout(() => {
        win.print();
        // win.close();
    }, 1500);

}
</script>

<template>
<Head title="Sampul Rapor" />
<div class="toolbar h-12 bg-slate-200 w-full flex items-center justify-between print:hidden px-4">
    <span>

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
    <div class="page cetak bg-slate-100 print:bg-white w-full bg-cover p-20 text-center font-serif">
        <!-- <h1 class="mt-10 text-center text-2xl font-black" >{{ sekolah.nama }}</h1>
        <h1 class="text-center" >{{ sekolah.alamat }}</h1> -->
        <h1 class="text-center text-2xl font-bold  uppercase">Laporan Hasil Capaian Kompetensi</h1>
        <h1 class="text-center text-2xl font-bold  uppercase">Peserta Didik</h1>
        <h1 class="text-center text-2xl font-bold  uppercase">Sekolah Dasar (SD)</h1>
        <h1 class="text-center text-xl font-bold  uppercase mt-8">Kurikulum Merdeka</h1>
        
        <img :src="sekolah.logo" alt="Logo" class="h-40 mx-auto mt-40 mb-20" @error="defaultLogo">
        <p class="text-center  mt-40">Nama Peserta Didik</p>
        <div class="box border-2 text-center w-[500px] p-2 mx-auto border-black border-double mb-4 font-bold">{{ siswa.nama }}</div>
        <p class="text-center uppercase mt-10">NIS / NISN</p>
        <div class="box border-2 text-center w-[500px] p-2 mx-auto border-black border-double mb-4 font-bold">{{ siswa.nis ?? '-' }} / {{ siswa.nisn }}</div>
        <!-- <h3 class="text-center font-bold text-xl uppercase mt-10">Kementerian Pendidikan dan Kebudayaan</h3>
        <h3 class="text-center font-bold text-xl uppercase">Republik Indonesia</h3> -->

        <h1 class="mt-20 text-center text-2xl font-black" >{{ sekolah.nama }}</h1>
        <h1 class="text-center" >{{ sekolah.alamat }}</h1>
    </div>
    <div>
        <!-- {{ sekolah }} -->
    </div>
</template>