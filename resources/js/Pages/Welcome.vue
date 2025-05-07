<script setup>
import { Link, router, usePage, Head } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import Header from "@/Layouts/Front/Header.vue";
import Footer from "@/Layouts/Front/Footer.vue";

const page = usePage();

// const toggleNav = () => {
//     const nav = document.querySelector(".nav-wrapper");
//     nav.classList.toggle("hidden");
// };
</script>

<template>
    <Head title="Sugeng Rawuh" />
    <div
        class="wrapper bg-gradient-to-br from-sky-400 to-indigo-500 min-h-screen w-screen relative overflow-x-hidden"
    >
        <!-- <div
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
                <div class="nav-wrapper hidden sm:block z-20">
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
                            href="/galeri"
                            >Galeri</Link
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
        </div> -->
        <Header />
        <div
            class="logo flex justify-center mt-8 gap-4 sm:gap-8 bg-white pt-16 pb-8"
        >
            <img
                src="/img/malangkab.png"
                alt="Kab Malang"
                class="h-16 sm:h-32"
            />
            <img src="/img/tutwuri.png" alt="Tutwuri" class="h-16 sm:h-32" />
            <div class="bg-white h-16 sm:h-32 text-center">
                <img
                    src="/img/ramah.png"
                    alt="Ramah"
                    class="h-14 sm:h-24 object-cover -translate-y-2"
                />
                <img
                    src="/img/bermutu.png"
                    alt="Bermutu"
                    class="h-16 sm:h-24 object-cover -translate-y-10 sm:-translate-y-12"
                />
            </div>
        </div>
        <img
            src="/img/laptop.png"
            alt=""
            class="absolute -right-40 sm:right-8 sm:bottom-0 sm:h-[600px]"
        />
        <div
            class="hero p-4 bg-white bg-opacity-15 backdrop-blur-0 relative my-8"
        >
            <h1
                class="title text-8xl text-white font-['Arial'] font-black text-shadow-lg"
            >
                P<span class="hidden sm:inline">USAT</span> K<span
                    class="hidden sm:inline"
                    >EGIATAN</span
                >
                G<span class="hidden sm:inline">URU</span>
            </h1>
            <h3
                class="subtitle text-4xl text-white font-black font-['Arial'] text-shadow-lg"
            >
                <span class="hidden">KORWIL DINAS PENDIDIKAN </span>KECAMATAN
            </h3>
            <h1
                class="title text-4xl sm:text-9xl text-white font-['Arial'] font-black text-shadow-lg"
            >
                WAGIR
            </h1>
        </div>
        <div class="kontak">
            <h1 class="p-4 bg-white sm:w-[20%] text-xl font-black">
                Website & Sosmed:
            </h1>
            <ul class="pl-6 mt-4">
                <li>
                    <a
                        href="https://pkgwagir.or.id"
                        class="flex items-center text-white gap-2 py-2"
                    >
                        <Icon icon="mdi:globe" class="text-xl" />
                        https://pkgwagir.or.id
                    </a>
                </li>
                <li>
                    <a
                        href="https://youtube.com"
                        class="flex items-center text-white gap-2 py-2"
                        target="_blank"
                    >
                        <Icon icon="mdi:youtube" class="text-xl" />
                        Literasi Wagir Untuk Bangsa
                    </a>
                </li>
            </ul>
        </div>
        <Footer class="mt-12" />
    </div>
</template>
