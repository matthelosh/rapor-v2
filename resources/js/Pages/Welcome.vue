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
                    <div class="hero ">
                        <Hero />
                    </div>
                    <div class="main-content">
                        <div class="main">
                            <template v-for="(post, p) in posts" :key="p">
                                <div class="card mb-6 bg-slate-100 grid grid-cols-1 md:grid-cols-4 gap-0 md:gap-2 hover:shadow transition-all duration-300 linear">
                                    <div class="cover col-span-1 h-[200px]">
                                        <img :src="post.cover" alt="Cover" class="w-full object-fit h-full" />
                                    </div>
                                    <article class="col-span-3 px-2 md:pr-4 pt-2">
                                        <h3 class="text-lg font-bold text-sky-800 hover:underline mb-4">
                                            <Link :href="`/baca/${post.slug}`">{{ post.title }}</Link>
                                        </h3>
                                        <p class="text-justify" v-html="post.content.substring(0, 250)"></p>
                                    </article>
                                </div>
                            </template>
                        </div>
                        <div class="side">
                            <Side :infos="infos" :agendas="agendas" :galeris="galeris" />
                            
                        </div>
                    </div>
                </div>
            </el-main>
            <Footer />
        </div>
    </div>
</template>

<style scoped>
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
    padding: 0;
}
.hero {
        width: 100%;
        /* display: flex;
        align-items: center;
        justify-content: center; */
        /* background: black; */
        height: auto;
        margin-bottom: 20px;
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
        /* display: flex;
        align-items: center;
        justify-content: center; */
        /* background: black; */
        /* height: 500px; */
        margin-bottom: 20px;
    }


}
</style>
