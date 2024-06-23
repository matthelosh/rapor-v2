<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { capitalize } from '@/helpers/String';

const page = usePage()
const sekolah = computed(() => {
    return page.props.sekolahs[0]
})
const defaultLogo = (e) => {
    e.target.src = '/img/tutwuri.png'
    e.onerror = null
}
</script>

<template>
    <div class="grid grid-cols-12 py-4 border-b border-double border-b-8 print:border-b-4 border-black">
        <div class="logo-kab col-span-3 print:col-span-2 flex items-center justify-center">
            <img src="/img/malangkab.png" alt="Logo Sekolah" @error="defaultLogo" class="h-32 print:h-20">
        </div>
        <div class="teks text-center font-serif col-span-6 print:col-span-8">
            <h3 class="uppercase font-bold text-lg print:text-[12pt]">Pemerintah Kabupaten Malang</h3>
            <h3 class="uppercase font-bold text-lg print:text[10pt]">Dinas Pendidikan dan Kebudayaan</h3>
            <h3 class="uppercase font-bold text print:text-[10pt]">Korwil Dinas Pendidikan Kecamatan Wagir</h3>
            <h1 class="uppercase font-bold text-2xl print:text-[14pt]">{{ sekolah.nama }}</h1>
            <p class="font-light print:text-[10pt]">Alamat: {{ capitalize(sekolah.alamat )}}, {{ capitalize(sekolah.desa) }}, Kec. {{ capitalize(sekolah.kecamatan) }}, Kode Pos {{ sekolah.kode_pos }}</p>
            <p class="font-light print:text-[10pt]">Telp: {{ capitalize(sekolah.telp )}}, Email: {{ sekolah.email }}, Website: {{ sekolah.website }}</p>

        </div>
        <div class="logo-sekolah col-span-3 print:col-span-2 flex items-center justify-center">
            <img :src="sekolah.logo" alt="Logo Sekolah" @error="defaultLogo" class="h-32 print:h-20">
        </div>
    </div>
</template>