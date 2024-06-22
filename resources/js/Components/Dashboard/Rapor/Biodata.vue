<script setup>
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

const page = usePage()
const props = defineProps({siswa: Object})
const emit = defineEmits(['close'])

const sekolah = computed(() => page.props.sekolahs[0])
const logo = computed(() => sekolah.value.logo ?? '/img/tutwuri.png')
const defaultLogo = (e) => {
    e.target.src = '/img/tutwuri.png'
    e.onerror = null
}
const close = () => {
    emit('close')
}



const siswa = props.siswa

const ortu = computed(() => {
    return {
        ayah: props.siswa.ortus.filter(ortu => ortu.relasi == 'Ayah')[0],
        ibu: props.siswa.ortus.filter(ortu => ortu.relasi == 'Ibu')[0],
        wali: props.siswa.ortus.filter(ortu => ortu.relasi == 'Wali')[0],

    }
})
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
        win.close();
    }, 1500);

}
</script>

<template>
<Head title="Sampul Rapor" />
<div class="toolbar h-12 bg-slate-200 w-full flex items-center justify-between print:hidden px-4">
    <span>

    </span>
    <div class="toolbar-items flex items-center">
        <el-button @click="cetak">
            <Icon icon="mdi:printer" />
        </el-button>
        <el-button circle type="danger" @click="close">
            <Icon icon="mdi:close" />
        </el-button>
    </div>
</div>
    <div class="page cetak bg-slate-100 print:bg-white w-full bg-cover p-20 font-serif">
        <h3 class="font-bold text-center uppercase text-xl">Identitas Peserta Didik</h3>
        <table class="mt-16  10">
            <tr>
                <td class="text-left">Nama Peserta Didik</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ siswa.nama }}</td>
            </tr>
            <tr>
                <td class="text-left">NISN / NIS</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ siswa.nisn }} / {{ siswa.nis }}</td>
            </tr>
            <tr>
                <td class="text-left w-[200px]">Tempat, Tanggal Lahir</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ siswa.tempat_lahir }}, {{ siswa.tanggal_lahir }}</td>
            </tr>
            <tr>
                <td class="text-left">Jenis Kelamin</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ siswa.jk }}</td>
            </tr>
            <tr>
                <td class="text-left">Agama</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ siswa.agama }}</td>
            </tr>
            <tr>
                <td class="text-left">Alamat Peserta Didik</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ siswa.alamat }}</td>
            </tr>
        </table>
        <table class="mt-8">
            <tr>
                <td class="text-left w-[200px]">Nama Orang Tua</td>
                <td class="text-left px-2"></td>
                <td class="text-left"></td>
            </tr>
            <tr>
                <td class="text-left">Ayah</td>
                <td class="text-left">:</td>
                <td class="text-left">{{ ortu.ayah.nama }}</td>
            </tr>
            <tr>
                <td class="text-left">Ibu</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ ortu.ibu.nama }}</td>
            </tr>
        </table>
        <table class="mt-8  10">
            <tr>
                <td class="text-left w-[200px]">Alamat Orang Tua</td>
                <td class="text-left px-2">&nbsp;</td>
                <td class="text-left">&nbsp;</td>
            </tr>
            <tr>
                <td class="text-left">Ayah</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ ortu.ayah.alamat }}</td>
            </tr>
            <tr>
                <td class="text-left">Ibu</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ ortu.ibu.alamat }}</td>
            </tr>
        </table>
        <table class="mt-8  10">
            <tr>
                <td class="text-left w-[200px]">No. Kontak Orang Tua</td>
                <td class="text-left px-2">&nbsp;</td>
                <td class="text-left">&nbsp;</td>
            </tr>
            <tr>
                <td class="text-left">Ayah</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ ortu.ayah.hp }}</td>
            </tr>
            <tr>
                <td class="text-left">Ibu</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ ortu.ibu.hp }}</td>
            </tr>
        </table>
        <table class="mt-8">
            <tr>
                <td class="text-left w-[200px]">Wali Peserta Didik</td>
                <td class="text-left px-2">&nbsp;</td>
                <td class="text-left">&nbsp;</td>
            </tr>
            <tr>
                <td class="text-left">Nama</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ ortu.wali?.nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-left">Alamat</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ ortu.wali?.alamat ?? '-' }}</td>
            </tr>
            <tr>
                <td class="text-left w-[100px]">Kontak</td>
                <td class="text-left px-2">:</td>
                <td class="text-left">{{ ortu.wali?.hp ?? '-' }}</td>
            </tr>
        </table>

        <div class="ttd flex justify-center mt-20">
            <div class="flex gap-2 ml-28">
                <div class="foto-holder border relative w-[3cm] h-[4cm] border-black border-dashed flex items-center justify-center">
                    Pas Foto <br /> (3x4cm)
                </div>
                <div>
                    <p>Kepala Sekolah,</p>



                    <p class="uppercase font-bold underline mt-20">{{ page.props.sekolahs[0].ks.nama }}, {{ page.props.sekolahs[0].ks.gelar_belakang }}</p>
                    <p class="leading-3">NIP. {{ page.props.sekolahs[0].ks.nip }}</p>
                </div>
            </div>
        </div>
    </div>
    <div>
        <!-- {{ sekolah }} -->
    </div>
</template>