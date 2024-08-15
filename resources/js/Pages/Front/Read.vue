<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
const page = usePage();

defineProps({
    post: Object,
    posts: Array,
});
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="common-layout">
        <el-container>
            <el-header style="background: #dfdfdf;">
                <div class="w-full flex justify-between items-center h-full px-2 md:px-[15%]">
                    <h3>Halo</h3>
                    <nav class="flex gap-4 uppercase">
                        <Link href="/">
                            Beranda
                        </Link>
                        <Link href="#">
                            Profil
                        </Link>
                        <Link href="#">
                            Berita
                        </Link>
                        <Link href="/login" class="flex items-center">
                            <Icon icon="mdi:application-import"  />
                        </Link>
                    </nav>
                </div>
            </el-header>
            <el-main>
                <div class="w-full px-2 md:px-[15%]">
                    <div class="hero h-[500px] bg-sky-100 w-full flex items-center justify-center mb-4 overflow-hidden">
                        <img :src="post.cover" alt="" class="w-full object-cover">
                        <!-- <h1 class="text-2xl font-bold text-sky-800">Selamat Datang</h1> -->
                    </div>
                    <div class="grid grid-cols-6 gap-4">
                        <div class="main col-span-4">
                            <article class="font-serif">
                                <h3 class="text-4xl mb-4">{{ post.title }}</h3>
                                <p v-html="post.content" class="text-justify tracking-wide leading-7"></p>
                            </article>
                        </div>
                        <div class="main col-span-2">
                            <el-divider>Berita Lainnya</el-divider>
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
        </el-container>
    </div>
</template>
