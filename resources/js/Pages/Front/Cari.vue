<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import Header from '@/Layouts/Front/Header.vue';
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Footer.vue'))

const page = usePage();
const search = ref('')
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
});

const searchPost = () => {
    router.visit(route('front.post.search', { _query: {q: search.value}}))
}

const params = computed(() => route().params)
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="common-layout">
        <div class="wrapper">
            <Header :appName="appName" />
            <el-main>
                <div class="main-container">
                    <div class="hero ">
                        <h1 class="text-2xl font-bold text-sky-800">Hasil Pencarian "{{ params.q }}"</h1>
                    </div>
                    <div class="main-content">
                        <div class="main">
                            <template v-for="(post, p) in posts" :key="p">
                                <div class="card mb-6 bg-slate-100 grid grid-cols-4 gap-2 hover:shadow transition-all duration-300 linear">
                                    <div class="cover col-span-1">
                                        <img :src="post.cover" alt="Cover" />
                                    </div>
                                    <article class="col-span-3 pr-4 pt-2">
                                        <h3 class="text-lg font-bold text-sky-800 hover:underline mb-4">
                                            <Link :href="`/baca/${post.slug}`">{{ post.title }}</Link>
                                        </h3>
                                        <p class="text-justify" v-html="post.content.substring(0, 250)"></p>
                                    </article>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </el-main>
            <Footer />
        </div>
    </div>
</template>

