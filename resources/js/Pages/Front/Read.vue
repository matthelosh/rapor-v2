<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import dayjs from 'dayjs';
import 'dayjs/locale/id';
dayjs.locale('id');

import Header from '@/Layouts/Front/Header.vue';
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Footer.vue'))

const page = usePage();
const search = ref('')
const props = defineProps({
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
    post: Object
});

const searchPost = () => {
    router.visit(route('front.post.search', { _query: {q: search.value}}))
}

onBeforeMount(() => {
    console.log(props.post)
})
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="common-layout">
        <div class="wrapper">
            <Header :appName="appName" />
            <el-main style="background: pink; padding: 0;">
                <div class="main-container md:px-[15%] bg-slate-100 md:py-8">
                    <div class="hero h-[500px]">
                        <!-- <h1 class="text-2xl font-bold text-sky-800">Selamat Datang</h1> -->
                        <img :src="post.cover" alt="" class="w-full h-full object-cover cover">
                    </div>
                    <el-container>
                        <el-row>
                            <el-col :span="18" :xs="24">
                                <div class="main p-4 col-span-1 md:col-span-3 bg-white">
                                    <article class="font-serif">
                                        <h3 class="text-xl md:text-2xl mb-2 font-bold text-slate-800">{{ post.title }}</h3>
                                        <p class="mb-4 font-bold text-slate-800 mt-6 flex gap-2">
                                            <el-tag type="danger">
                                                <span class="flex items-center gap-1 py-2" >
                                                    <Icon icon="mdi:account-circle" class="text-xl" />
                                                    {{ post.author.userable ? post.author.userable.name : post.author.name }}
                                                </span>
                                            </el-tag>
                                            <el-tag type="primary">
                                                <span class="flex items-center gap-1 py-2" >
                                                    <Icon icon="mdi:calendar" class="text-xl" />
                                                    {{ dayjs(post.updated_at).format('DD MMMM YYYY H:mm:ss')}}
                                                </span>
                                            </el-tag>
                                            <el-tag type="success">
                                                <span class="flex items-center gap-1 py-2" >
                                                    <Icon icon="mdi:tag" class="text-xl" />
                                                    {{ post.category}}
                                                </span>
                                            </el-tag>
                                        </p>
                                        <p v-html="post.content" class="text-justify leading-8 text-lg mt-8"></p>
                                    </article>
                                </div>
                            </el-col>
                            <el-col :span="6" :xs="24">
                                <div class="side p-4 bg-slate-50 md:col-span-1 col-span-1">
                                <el-input placeholder="Cari Tulisan" v-model="search">
                                    <template #suffix>
                                        <el-button text size="small" @click="searchPost">
                                            <Icon icon="mdi:magnify" />
                                        </el-button>
                                    </template>
                                </el-input>
                            <el-divider>Berita Lain</el-divider>
                            <ol class="pl-6">
                                <li v-for="(post, p) in posts" :key="p" class="list-disc mb-2">
                                    <Link :href="`/baca/${post.slug}`" class="hover:underline leading-0">
                                        {{ post.title }}
                                    </Link>
                                </li>
                            </ol>
                        </div>
                            </el-col>
                        </el-row>
                    </el-container>
                    <!-- <div class="main-content bg-white grid grid-cols-1 md:grid-cols-4">
                        <div class="main p-4 col-span-1 md:col-span-3">
                            <article class="font-serif">
                                <h3 class="text-xl md:text-2xl mb-2 font-bold text-slate-800">{{ post.title }}</h3>
                                <p class="mb-4 font-bold text-slate-800 mt-6 flex gap-2">
                                    <el-tag type="danger">
                                        <span class="flex items-center gap-1 py-2" >
                                            <Icon icon="mdi:account-circle" class="text-xl" />
                                            {{ post.author.userable ? post.author.userable.name : post.author.name }}
                                        </span>
                                    </el-tag>
                                    <el-tag type="primary">
                                        <span class="flex items-center gap-1 py-2" >
                                            <Icon icon="mdi:calendar" class="text-xl" />
                                            {{ dayjs(post.updated_at).format('DD MMMM YYYY H:mm:ss')}}
                                        </span>
                                    </el-tag>
                                    <el-tag type="success">
                                        <span class="flex items-center gap-1 py-2" >
                                            <Icon icon="mdi:tag" class="text-xl" />
                                            {{ post.category}}
                                        </span>
                                    </el-tag>
                                </p>
                                <p v-html="post.content" class="text-justify leading-8 text-lg mt-8"></p>
                            </article>
                        </div>
                        <div class="side p-4 bg-slate-50 md:col-span-1 col-span-1">
                            <el-input placeholder="Cari Tulisan" v-model="search">
                                <template #suffix>
                                    <el-button text size="small" @click="searchPost">
                                        <Icon icon="mdi:magnify" />
                                    </el-button>
                                </template>
                            </el-input>
                            <el-divider>Berita Lain</el-divider>
                            <ol class="pl-6">
                                <li v-for="(post, p) in posts" :key="p" class="list-disc mb-2">
                                    <Link :href="`/baca/${post.slug}`" class="hover:underline leading-0">
                                        {{ post.title }}
                                    </Link>
                                </li>
                            </ol>
                        </div>
                    </div> -->
                </div>
            </el-main>
            <Footer />
        </div>
    </div>
</template>

<style>
</style>
