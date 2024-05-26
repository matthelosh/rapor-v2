<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="text-center ">
            <Link href="/" class="text-center">
                <img src="/img/tutwuri.png" alt="Logo" class="h-28 mx-auto">
            </Link>
            <h1 class="text-center font-bold text-xl uppercase text-slate-700 mt-4">Rapor Sekolah Dasar</h1>
        </div>

        <div
            class="w-full sm:max-w-md mt-4 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
