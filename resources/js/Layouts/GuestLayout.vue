<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-base-200 px-4">
        <div class="text-center mb-6">
            <Link href="/" class="text-center inline-block">
                <img
                    src="/img/tutwuri.png"
                    alt="Logo"
                    class="h-28 mx-auto"
                />
            </Link>
            <h1 class="text-center font-bold text-xl uppercase text-primary mt-4">
                MASUKKAN USERNAME DAN PASSWORD
            </h1>
        </div>

        <div
            class="w-full sm:max-w-md card bg-base-100 shadow-xl border border-base-300"
        >
            <div class="card-body pt-10">
                <slot />
            </div>
        </div>
    </div>
</template>
