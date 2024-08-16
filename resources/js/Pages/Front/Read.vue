<script setup>
import { ref, computed } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import Header from '@/Layouts/Front/Header.vue';

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
    post: Object
});

const searchPost = () => {
    router.visit(route('front.post.search', { _query: {q: search.value}}))
}
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="common-layout">
        <div class="wrapper">
            <Header :appName="appName" />
            <el-main>
                <div class="main-container">
                    <div class="hero ">
                        <h1 class="text-2xl font-bold text-sky-800">Selamat Datang</h1>
                        <img :src="post.cover" alt="">
                    </div>
                    <div class="main-content">
                        <div class="main">
                            <article class="font-serif">
                                <h3 class="text-2xl mb-2 font-bold text-slate-800">{{ post.title }}</h3>
                                <p class="mb-4 font-bold text-slate-800 text-right">
                                    <el-tag type="error">
                                        <span class="flex items-center gap-1 py-2" >
                                            <Icon icon="mdi:account-circle" class="text-xl" />
                                            {{ post.author.userable ? post.author.userable.name : post.author.name }}
                                        </span>
                                    </el-tag>
                                    <el-tag type="primary">
                                        <span class="flex items-center gap-1 py-2" >
                                            <Icon icon="mdi:calendar" class="text-xl" />
                                            {{ post.updated_at}}
                                        </span>
                                    </el-tag>
                                    <el-tag type="success">
                                        <span class="flex items-center gap-1 py-2" >
                                            <Icon icon="mdi:tag" class="text-xl" />
                                            {{ post.category}}
                                        </span>
                                    </el-tag>
                                </p>
                                <p v-html="post.content" class="text-justify leading-8 text-lg"></p>
                            </article>
                        </div>
                        <div class="side">
                            <el-input placeholder="Cari Tulisan" v-model="search">
                                <template #suffix>
                                    <el-button text size="small" @click="searchPost">
                                        <Icon icon="mdi:magnify" />
                                    </el-button>
                                </template>
                            </el-input>
                            <el-divider>Berita Lain</el-divider>
                            <ol class="pl-6">
                                <li v-for="(post, p) in posts" :key="p" class="list-disc">
                                    <Link :href="`/baca/${post.slug}`" class="hover:underline">
                                        {{ post.title }}
                                    </Link>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </el-main>
            <el-footer style="background: #facefd;">
                <div class="w-full flex justify-between items-center h-full px-2 md:px-[15%]">
                    <p>&copy; {{ new Date().getFullYear() }}</p>
                </div>
            </el-footer>
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
        grid-template-areas: 'post post post post side side';
        gap: 20px;
    }
    .hero {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: black;
        height: 500px;
        margin-bottom: 20px;
    }


}
</style>
