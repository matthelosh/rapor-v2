<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-sky-400 to-indigo-500 px-4 relative"
    >
        <div class="text-center">
            <Link href="/" class="text-center">
                <img
                    src="/img/tutwuri.png"
                    alt="Logo"
                    class="h-28 mx-auto animate-bounce"
                />
            </Link>
            <h1 class="text-center font-bold text-xl uppercase text-white mt-4">
                MASUKKAN USERNAME DAN PASSWORD
            </h1>
        </div>


        <div
            class="w-full sm:max-w-md mt-4 px-6 pt-8 pb-4 bg-white bg-opacity-70 backdrop-blur-sm shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
