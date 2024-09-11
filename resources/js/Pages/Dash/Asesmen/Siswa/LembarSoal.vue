<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, usePage, router, Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { fotoSiswa } from '@/helpers/Gambar';
import DashLayout from '@/Layouts/DashLayout.vue';
import axios from 'axios';
const SoalAsesmen = defineAsyncComponent(() => import('@/Components/Dashboard/Asesmen/SoalAsesmen.vue'))
const page = usePage()
const props = defineProps({asesmen: Object, siswa: Object})
const setuju = ref(false)
const showSoalAsesmen = ref(false)
const proses = ref(null)

const kerjakan = () => {
    axios.post(route('asesmen.siswa.kerjakan.mulai', {kode: props.asesmen.kode, _query:{siswaId: props.siswa.nisn, prosesId: props.asesmen.proses?.id ?? null}}), 
    { siswaId: props.siswa.nisn })
    .then(res => {
        proses.value = res.data.proses
        showSoalAsesmen.value = true
        const elem = document.documentElement
        elem.requestFullscreen()
    }).catch(err => {
        console.log(err)
    })
}

const closeSoalAsesmen = () => {
    // const elem = document.documentElement
    showSoalAsesmen.value = false
    document.exitFullscreen()
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
            <div class="min-h-[70vh]">
                <h3 class="text-center md:text-2xl font-bold text-sky-600 mb-4">Pastikan data kamu sudah benar!</h3>
                <el-divider></el-divider>
                <h3 class="font-bold text-center">SOAL: {{ props.asesmen.nama }}</h3>
                <h3 class="font-bold text-center">Proses ID: {{ props.asesmen.proses?.id }}</h3>
                <h3 class="font-bold text-center mb-4">Soal Terjawab: {{ props.asesmen.jawabans?.length }}</h3>
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
                <div class="md:w-[50%] mx-auto">
                    <p class="mt-4 text-sm font-bold">Petunjuk pengerjaan</p>
                    <div class="border border-sky-500 rounded p-2 min-h-[150px] bg-white">
                        <p class="text-sm text-justify">{{ props.asesmen.deskripsi }}</p>
                    </div>
                </div>
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
<SoalAsesmen v-if="showSoalAsesmen" :show="showSoalAsesmen" :asesmen="props.asesmen" :proses="proses" :siswa="props.siswa" @close="closeSoalAsesmen" />
</template>