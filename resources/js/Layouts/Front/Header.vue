<script setup>
import { ref, computed, useTemplateRef, onMounted } from "vue";
import { Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { onClickOutside } from "@vueuse/core";

import menus from "@/helpers/frontMenu.json";
const navWrapper = ref(null);

// if (window.innerWidth < 768 && navWrapper) {
//     if (navWrapper.classList?.contains("hidden") === false) {
//         onClickOutside((navWrapper, event) => {
//             console.log(navWrapper, event);
//             toggleNav();
//         });
//     }
// }

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    appName: String,
});

const showDrawer = ref(false);
const toggleNav = () => {
    const nav = document.querySelector(".nav-wrapper");
    nav.classList.toggle("hidden");
};
onMounted(() => {
    onClickOutside(navWrapper, (event) => {
        if (
            window.innerWidth < 768 &&
            navWrapper.value &&
            !navWrapper.value.classList.contains("hidden")
        ) {
            // console.log(navWrapper.value, event);
            toggleNav();
        }
    });
});
</script>

<template>
    <el-affix :offset="0">
        <div
            class="header flex justify-between items-start py-2 px-4 text-white relative h-[50px]"
        >
            <Link href="/" class="logo flex items-center gap-1">
                <img src="/img/logo_pkg.png" alt="" class="h-10" />
                <div class="logo-text flex flex-col items-start justify-start">
                    <h3 class="leading-3 m-0 p-0">P.K.G.</h3>
                    <small class="leading-3 m-0 p-0">Kecamatan Wagir</small>
                </div>
            </Link>
            <div class="header-navs flex flex-col items-end justify-start h-10">
                <button @click="toggleNav" class="sm:hidden translate-y-2">
                    <Icon icon="mdi:menu" class="text-xl" />
                </button>
                <div class="nav-wrapper hidden sm:block z-20" ref="navWrapper">
                    <div
                        class="header-nav flex flex-col sm:flex-row bg-white sm:bg-transparent bg-opacity-40 backdrop-blur p-2 rounded shadow sm:shadow-none gap-2"
                    >
                        <Link
                            class="hover:underline px-2 sm:text-white text-sky-700 text-lg"
                            href="/"
                            >Beranda</Link
                        >
                        <Link
                            class="hover:underline px-2 sm:text-white text-sky-700 text-lg"
                            href="/profil"
                            >Profil</Link
                        >
                        <Link
                            class="hover:underline px-2 sm:text-white text-sky-700 text-lg"
                            href="/layanan"
                            >Layanan</Link
                        >
                        <Link
                            class="hover:underline px-2 sm:text-white text-sky-700 text-lg"
                            href="/berita"
                            >Berita</Link
                        >
                        <Link
                            class="hover:underline px-2 sm:text-white text-sky-700 text-lg"
                            href="/info"
                            >Pengumuman</Link
                        >
                        <Link
                            class="hover:underline px-2 sm:text-white text-sky-700 text-lg"
                            href="/galeri"
                            >Galeri</Link
                        >
                        <Link
                            class="hover:underline px-2 sm:text-white text-sky-700 text-lg"
                            href="/agenda"
                            >Agenda</Link
                        >
                        <Link
                            class="hover:underline px-2 sm:text-white text-sky-700 text-lg"
                            href="/sertifikat"
                            >Sertifikat</Link
                        >
                        <Link
                            href="/login"
                            class="flex items-center bg-sky-400 sm:bg-transparent border px-2 rounded text-lg sm:hover:bg-white sm:hover:text-sky-600 transition-all duration-300"
                        >
                            <Icon icon="mdi-light:login" />
                            Masuk
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </el-affix>
</template>

<style>
/* header {
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
} */
</style>
