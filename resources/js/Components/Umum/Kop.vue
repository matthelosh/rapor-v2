<script setup>
import { usePage } from "@inertiajs/vue3";
import { capitalize } from "@/helpers/String";

const page = usePage();
const sekolah = page.props.sekolahs[0];
</script>

<template>
    <div
        class="grid grid-cols-6 p-4 print:p-0 border-b border-b-4 border-b-black border-double"
    >
        <div class="kab col-span-1 flex justify-center items-center">
            <img
                src="/img/malangkab.png"
                alt="Logo Kab/Kota"
                class="w-[100px] print:w-[80px]"
            />
        </div>
        <div class="identitas col-span-4 font-serif text-black">
            <h3 class="leading-5 text-center font-bold uppercase">
                Pemerintah Kabupaten {{ sekolah.kabupaten }}
            </h3>
            <h3 class="leading-5 text-center font-bold uppercase">
                Korwil Dinas Pendidikan Kecamatan {{ sekolah.kecamatan }}
            </h3>
            <h3 class="leading-5 text-center font-bold uppercase text-xl">
                {{ sekolah.nama }}
            </h3>
            <h3 class="leading-5 text-center font-bold uppercase">
                NPSN: {{ sekolah.npsn }}
            </h3>
            <p class="leading-4 text-center text-sm">
                Alamat: {{ capitalize(sekolah.alamat) }}, {{ sekolah.desa }},
                Kode Pos {{ sekolah.kode_pos }}
            </p>
            <p class="leading-4 text-center text-sm">
                Telp: {{ sekolah.telp }}, Email: {{ sekolah.email }}, Website:
                {{ sekolah.website }}
            </p>
        </div>
        <div class="tutwuri col-span-1 flex items-center justify-center">
            <el-image :src="sekolah.logo" style="width: 80px; margin: 0 auto">
                <template #error>
                    <img src="/img/tutwuri.png" alt="Tutwuri" />
                </template>
            </el-image>
        </div>
    </div>
</template>
