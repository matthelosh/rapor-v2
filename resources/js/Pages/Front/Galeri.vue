<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { capitalize } from '@/helpers/String'

import Header from '@/Layouts/Front/Header.vue';
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Footer.vue'))

const props = defineProps({galeris: Object, appName: String})
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="common-layout">
        <div class="wrapper bg-slate-800 h-[100vh]">
            <Header :appName="props.appName" />
            <el-main>
                <el-row justify="center">
                    <el-col :span="17" :xs="24">
                        <div class="columns-2 md:columns-4 gap-4 space-y-4">
                            <template v-for="(galeri, g) in galeris.data" :key="g">
                                <el-card class="group" style="position: relative;">
                                    <el-image :src="galeri.url" :preview-src-list="galeris.data.map(galeri => galeri.url)"></el-image>
                                    <h3 class="text-center text-sky-50 hidden group-hover:inline absolute left-[50%] -translate-x-[50%] bottom-8 transition-all easy-in duration-300 bg-slate-800 px-4 backdrop-blur bg-opacity-30">{{ capitalize(galeri.nama) }}</h3>
                                </el-card>
                            </template>
                        </div>
                    </el-col>
                </el-row>
            </el-main>
            <Footer />
        </div>
    </div>
</template>