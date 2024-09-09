<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, usePage, router, Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { fotoSiswa } from '@/helpers/Gambar';
import DashLayout from '@/Layouts/DashLayout.vue';
const SoalAsesmen = defineAsyncComponent(() => import('@/Components/Dashboard/Asesmen/SoalAsesmen.vue'))
const page = usePage()
const props = defineProps({asesmen: Object, siswa: Object})
const setuju = ref(false)
const showSoalAsesmen = ref(false)

const kerjakan = () => {
    showSoalAsesmen.value = true
}
</script>

<template>
<Head title="Asesmen" />

<DashLayout>
    <template #header>
        Lembar Soal
    </template>
    <template #default>
        <el-card body-class="bg-sky-50">
            <template #header>
                <div class="flex items-center justify-between">
                    <h3></h3>
                    <Link :href="route('asesmen.siswa')" class="text-red-500 hover:cursor-pointer">
                        <Icon icon="mdi:close" />
                    </Link>
                </div>
            </template>
            <h3 class="text-center font-bold text-sky-600 mb-4">Pastikan data kamu sudah benar!</h3>
            <h3 class="font-bold text-center mb-4">SOAL: {{ props.asesmen.nama }}</h3>
            <div class="flex justify-center">
                <el-avatar :src="page.props.auth.user.userable.foto" :size="100">
                    <template #error>
                        <img :src="fotoSiswa(page.props.auth.user.userable)" alt="">
                    </template>
                </el-avatar>
            </div>
            <h3 class="text-center uppercase font-bold text-green-700 mt-4">{{ props.siswa?.nama }}</h3>
            <h3 class="text-center"><small>NISN: {{ props.siswa.nisn }}</small></h3>
            <h3 class="text-center">Kelas: {{ props.siswa.rombels[0].label }}</h3>
            <h3 class="text-center font-bold text-slate-700">{{ props.siswa.sekolah.nama }}</h3>
            <p class="mt-4 text-sm font-bold">Petunjuk pengerjaan</p>
            <div class="border border-sky-500 rounded p-2 min-h-[150px] bg-white">
                <p class="text-sm text-justify">{{ props.asesmen.deskripsi }}</p>
            </div>
            <template #footer>
                <div class="flex justify-between">
                    <el-checkbox v-model="setuju">Lanjutkan</el-checkbox>
                    <el-button type="primary" :disabled="!setuju" @click="kerjakan">Kerjakan Asesmen</el-button>
                </div>
            </template>
        </el-card>
    </template>
</DashLayout>
<SoalAsesmen v-if="showSoalAsesmen" :show="showSoalAsesmen" :asesmen="props.asesmen" :siswa="props.siswa" @close="showSoalAsesmen = false" />
</template>