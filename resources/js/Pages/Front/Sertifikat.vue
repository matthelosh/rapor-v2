<script setup>
import { ref, computed, defineAsyncComponent, onMounted } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import Header from '@/Layouts/Front/Header.vue';
import axios from 'axios';
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Footer.vue'))
const Cetak = defineAsyncComponent(() => import('@/Components/Sertifikat/Cetak.vue'))

const mode = ref('list')

const page = usePage();
const loading = ref(false)
const guru = ref({})
defineProps({
    agenda: Object,
    sekolahs: Array,
    appName: String,
    data: Array
});

const sertifikat = ref(null)

const cari = async() => {
    router.visit(route('sertifikat.front', {
        _query: guru.value
    }), {
        onSuccess: () => {
            mode.value = ''
        }
    })
}

const cetak = (item) => {
    sertifikat.value = item
    mode.value = 'cetak'
}

onMounted(() => {
})
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="common-layout">
        <div class="wrapper h-screen bg-slate-200">
            <Header :appName="'Sertifikat Workkshop'" />
            <el-main style="height: 90vh;">
                <!-- {{ data }} -->
                <div class="h-full  flex items-center justify-center " v-if="mode == 'list'">
                    <el-card class="w-[60%]" v-if="!data">
                        <template #header>
                            <h3>Cari Sertifikat</h3>
                        </template>
                        <template #default>
                            <el-form label-position="top" v-loading="loading" @submit.prevent="cari">
                                <el-form-item label="Nama Peserta">
                                    <el-input v-model="guru.nama" placeholder="Masukkan Nama Guru"></el-input>
                                </el-form-item>
                                <el-form-item label="Asal Lembaga">
                                    <el-select v-model="guru.sekolah_id" placeholder="Pilih Sekolah" filterable clearable>
                                        <template v-for="sekolah in sekolahs">
                                            <el-option :value="sekolah.npsn" :label="sekolah.nama"></el-option>
                                        </template>
                                    </el-select>
                                </el-form-item>
                            </el-form>
                        </template>
                        <template #footer>
                            <div class="flex justify-center">
                                <el-button type="primary" :loading="loading" @click="cari">
                                    <Icon icon="mdi:magnify" class="mr-1" />
                                    Cari
                                </el-button>
                            </div>
                        </template>
                    </el-card>

                    <div v-else>
                        <div class="columns-3">
                            <template v-for="sertifikat in data">
                                <el-card class="mb-4">
                                    <h3 class="text-center text-xl">Sertifikat</h3>
                                    <p class="text-center">Nomor: {{ sertifikat.nomor }}</p>
                                    <h3 class="text-center text-sky-500">{{ sertifikat.penerima.nama }}</h3>
                                    <div class="flex justify-center pt-4">
                                        <el-button type="primary" @click="cetak(sertifikat)" class="flex items-center gap-1">
                                            <Icon icon="mdi:printer" />
                                            Cetak
                                        </el-button>
                                    </div>
                                </el-card>
                            </template>
                        </div>
                    </div>
                </div>
                <Cetak v-else :sertifikat="sertifikat" @close="mode = 'list'" />
            </el-main>
            <Footer />
        </div>
    </div>
</template>

