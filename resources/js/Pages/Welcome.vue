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
    <div class="common-layout">
        <el-container>
            <el-header style="background: #facefd;">
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
                    </nav>
                </div>
            </el-header>
            <el-main>
                <div class="w-full px-2 md:px-[15%]">
                    <div class="hero h-[500px] bg-sky-100 w-full flex items-center justify-center mb-4">
                        <h1 class="text-2xl font-bold text-sky-800">Selamat Datang</h1>
                    </div>
                    <div class="grid grid-cols-6 gap-4">
                        <div class="main col-span-4">
                            {{ posts }}
                        </div>
                        <div class="main col-span-2">
                            {{ infos }}
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
