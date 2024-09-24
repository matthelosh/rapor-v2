<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import Header from '@/Layouts/Front/Header.vue';
const Hero = defineAsyncComponent(() => import('@/Layouts/Front/Hero.vue'))
const Side = defineAsyncComponent(() => import('@/Layouts/Front/Side.vue'))
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Footer.vue'))

const page = usePage();

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    appName: String,
    posts: Array,
    infos: Array,
    agendas: Array,
    galeris: Array,
});


</script>

<template>
    <Head title="Selamat Datang" />
    <div class="common-layout">
        <div class="wrapper">
            <Header :appName="appName" />
            <el-main>
                <div class="main-container">
                        <el-row :gutter="20" justify="center">
                            <el-col :span="24" :md="18" :lg="24" :xl="18">
                                <div class="hero ">
                                    <Hero />
                                </div>

                            </el-col>
                        </el-row>
                    <div class="main-content py-10">
                        <el-container>
                            <el-row :gutter="20" justify="center">
                                <el-col :span="24" :md="18" :lg="18" :xl="12">
                                    <div class="grid grid-cols-1 gap-0 md:gap-4 md:grid-cols-3">
                                        <template v-for="(post, p) in posts" :key="p">
                                            <div class="columns-1 col-span-1 shadow mb-4 p-2 border" :class="p === 0 ? 'md:col-span-3':''">
                                                <div class="cover col-span-1 h-[200px]" :class="p === 0 ? 'md:h-[350px]' : ''">
                                                    <img :src="post.cover" alt="Cover" class="w-full object-cover h-full" />
                                                </div>
                                                <article class="col-span-3 px-2 md:pr-4 pt-2">
                                                    <h3 class="text-lg font-bold text-sky-800 hover:underline mb-4">
                                                        <Link :href="`/baca/${post.slug}`">{{ post.title }}</Link>
                                                    </h3>
                                                    <p class="text-justify" v-html="post.content.substring(0, p === 0 ? 700 : 250)"></p>
                                                </article>
                                            </div>
                                        </template>
                                    </div>
                                </el-col>
                                <el-col :span="24" :md="6">
                                    <Side :infos="infos" :agendas="agendas" :galeris="galeris" />
                                </el-col>
                            </el-row>
                        </el-container>
                    </div>
                </div>
            </el-main>
            <Footer />
        </div>
    </div>
</template>

<style scoped>

</style>
