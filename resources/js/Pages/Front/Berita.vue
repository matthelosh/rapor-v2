<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import Header from '@/Layouts/Front/Header.vue';
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Footer.vue'))

const page = usePage();
const search = ref('')
const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    appName: String,
    beritas: Object,
});

const searchPost = () => {
    router.visit(route('front.post.search', { _query: {q: search.value}}))
}

const currentPage = ref(1)
const currentChange = (n) => {
    currentPage.value = n
    router.visit(props.beritas.path+"?page="+n, {preserveState: true})
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
                        <h1 class="text-2xl font-bold text-sky-800">Berita PKG SD Kecamatan Wagir</h1>
                    </div>
                    <div class="main-content">
                        <el-pagination :total="props.beritas.total" layout="total, prev, pager, next" @current-change="currentChange" :current-page="currentPage"></el-pagination>
                        <div class="main w-full columns-1 md:columns-4 gap-4 space-y-4 mx-auto">
                            <template v-for="(berita, b) in props.beritas.data" :key="b">
                                <div class="card bg-slate-100 relative overflow-hidden rounded-md" :class="`${[0,3,4,7].includes(b) ? 'md:h-[200px] h-[200px]' : 'md:h-[500px] h-[200px]'} bg-[url('${berita.cover}')]`">
                                <!-- <div class="card bg-slate-100 relative overflow-hidden rounded-md" :class="`${b ===( Math.floor(Math.random() * (beritas.data.length -1 ))) ? 'md:h-[200px] h-[200px]' : 'md:h-[500px] h-[200px]'} bg-[url('${berita.cover}')]`"> -->
                                    <div class="absolute top-0 right-0 bottom-0 left-0 bg-slate-500 bg-opacity-60 hover:backdrop-blur-sm backdrop-blur-none p-4 hover:cursor-pointer group">
                                        <h3 class="group-hover:text-white font-bold">
                                            {{ berita.title }}
                                        </h3>
                                        <p class="group-hover:text-white" v-html="berita.content.substring(0, 200)">
                                            
                                        </p>
                                    </div>
                                    <img :src="berita.cover" alt="Cover" class="h-full w-full object-cover ">
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
        height: 300px;
        margin-bottom: 20px;
    }


}
</style>
