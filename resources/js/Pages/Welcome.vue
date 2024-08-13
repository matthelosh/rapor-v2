<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";

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
    posts: Array,
    infos: Array,
});
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="wrapper bg-sky-100">
        <header
            class="w-full h-16 bg-slate-200 dark:bg-slate-500 dark:text-white flex justify-between items-center px-2 md:px-40"
        >
            <div class="title">
                <h1>PKG Kec. Wagir</h1>
            </div>
            <nav class="flex items-center gap-2">
                <Link href="/">Beranda</Link>
            </nav>
        </header>
        <div
            class="hero h-[500px] w-full md:w-[60%] mt-4 mx-auto bg-sky-300 dark:bg-sky-500 dark:text-sky-50 flex items-center justify-center"
        >
            <div class="text-center">
                <h3 class="text-4xl tracking-wider">Halo Bos!</h3>
                <small>Semoga Bermanfaat</small>
            </div>
        </div>
        <main class="py-4 w-full md:w-[60%] mx-auto dark:bg-slate-200 bg-white">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div class="posts col-span-1 md:col-span-4">
                    <template v-for="(post, p) in posts" :key="p">
                        <div
                            class="card grid grid-cols-3 gap-2 my-4 bg-white shadow"
                        >
                            <div class="cover col-span-1">
                                <img :src="post.cover" alt="Cover" />
                            </div>
                            <article class="col-span-2 p-2 pr-4">
                                <h3
                                    class="text-2xl font-bold tracking-wide text-justify"
                                >
                                    <Link :href="`/baca/${post.slug}`">
                                        {{ post.title }}
                                    </Link>
                                </h3>
                                <p
                                    v-html="post.content.substring(0, 300)"
                                    class="text-justify"
                                ></p>
                            </article>
                        </div>
                    </template>
                </div>
                <div class="side col-span-1 md:col-span-2 mx-2">
                    <el-divider>Pengumuman</el-divider>
                    <div class="infos">
                        <ol>
                            <li
                                v-for="(info, i) in infos"
                                :key="i"
                                class="my-2 text-sky-800 hover:underline leading-5"
                            >
                                <Link :href="`/baca/${info.slug}`">
                                    <h3>{{ info.title }}</h3>
                                </Link>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </main>
        <footer class="w-full"></footer>
    </div>
</template>
