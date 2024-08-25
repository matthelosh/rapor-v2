<script setup>
import { ref, computed } from 'vue'
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

<style>
header {
    height: 60px;
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    position: sticky;
    top: 0;
}
.navbar .navbar-container {
    color: #efefef;
    height: 100%;   
    background: rgb(27, 110, 226);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}

.main-container {
    padding: 0 20px;
}

@media only screen and (min-width: 736px) {
    .main {
        grid-area: post;
    }
    .side {
        grid-area: side;
    }
    .main-container {
        padding: 0 15%;
    }
    .navbar .navbar-container {
        padding: 0 15.5%;
    }

    .main-content {
        display: grid;
        grid-template-areas: 'post post post post post post';
    }
    .hero {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgb(232, 242, 246);
        height: 500px;
        margin-bottom: 20px;
    }


}
</style>
