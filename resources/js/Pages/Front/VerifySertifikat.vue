<script setup>
import { ref, computed, defineAsyncComponent, onMounted } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import dayjs from 'dayjs';
import 'dayjs/locale/id';
dayjs.locale('id');
import Header from '@/Layouts/Front/Header.vue';
import axios from 'axios';
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Footer.vue'))

const mode = ref('list')

const page = usePage();
const loading = ref(false)
const guru = ref({})
defineProps({
    sertifikat: Object,
});

const nomor = ref(null)

const verify = async() => {
    router.get(route('sertifikat.verify', {_query: {nomor: nomor.value}}), {} ,{
        onStart: ()=> loading.value = true,
        onFinish: loading.value = false
    })
} 
onMounted(() => {
})
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="common-layout">
        <div class="wrapper h-screen bg-slate-200">
            <Header :appName="'Sertifikat Workkshop'" />
            <el-main style="height: 90vh;" >
                <div v-if=sertifikat class="h-full flex items-center justify-center relative">
                    <div class="absolute top-[30%] ">
                        <Icon icon="mdi:check-bookmark" class="text-green-500 text-6xl animate-bounce mx-auto" />
                    </div>
                    <el-card>
                        <h3 class="text-center text-sky-700 font-bold text-xl">Selamat!</h3>
                        <h3 class="text-center font-bold text-slate-700">Sertifikat Terverifikasi</h3>
                        <el-divider>Detail</el-divider>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="px-1">Nomor</td>
                                    <td class="px-1">:</td>
                                    <td class="px-1">{{ sertifikat.nomor }}</td>
                                </tr>
                                <tr>
                                    <td class="px-1">Nama Kegiatan</td>
                                    <td class="px-1">:</td>
                                    <td class="px-1">{{ sertifikat.workshop?.nama }}</td>
                                </tr>
                                <tr>
                                    <td class="px-1">Nama Penerima</td>
                                    <td class="px-1">:</td>
                                    <td class="px-1">{{ sertifikat.penerima?.nama }}</td>
                                </tr>
                                <tr>
                                    <td class="px-1">Tanggal Kegiatan</td>
                                    <td class="px-1">:</td>
                                    <td class="px-1">{{ dayjs(sertifikat.workshop?.mulai ).format('DD MMMM YYYY') }} s/d {{ dayjs(sertifikat.workshop?.selesai ).format('DD MMMM YYYY') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </el-card>
                </div>
                <div v-else class="h-full flex items-center justify-center">
                    <el-card class="w-[60%] mx-auto" v-loading="loading">
                        <h3 class="text-center font-bold text-lg text-skt-700 mb-2">
                            Masukkan Nomor Sertifikat:
                        </h3>
                        <el-input v-model="nomor" placeholder="Nomor Sertifikat"></el-input>
                        <div class="flex justify-center">
                            <el-button class="mt-4 mx-auto" type="primary" @click="verify">
                                <Icon icon="mdi:magnify" />
                                Verifikasi
                            </el-button>
                        </div>
                    </el-card>
                </div>
            </el-main>
            <Footer />
        </div>
    </div>
</template>

