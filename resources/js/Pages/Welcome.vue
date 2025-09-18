<script setup>
import { Link, router, usePage, Head } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { ElCard } from 'element-plus';
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
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-blue-100 to-blue-50">
        <!-- Navigation -->
        <nav class="relative z-10 px-6 py-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <img src="/img/logo_pkg.png" alt="PKG Wagir" class="h-10 w-10" />
                    <span class="text-blue-900 font-bold text-xl">PKG Wagir</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="/" class="text-blue-900 hover:text-blue-600 transition-colors">Beranda</a>
                    <a href="/profil" class="text-blue-900 hover:text-blue-600 transition-colors">Profil</a>
                    <a href="/layanan" class="text-blue-900 hover:text-blue-600 transition-colors">Layanan</a>
                    <a href="/galeri" class="text-blue-900 hover:text-blue-600 transition-colors">Galeri</a>
                </div>
                <a href="/login" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full transition-colors font-medium">
                    Masuk
                </a>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative px-6 py-20">
            <div class="max-w-7xl mx-auto text-center">
                <div class="mb-8">
                    <img src="/img/malangkab.png" alt="Kab Malang" class="h-20 mx-auto mb-4 opacity-80" />
                    <div class="flex justify-center items-center space-x-4 mb-6">
                        <img src="/img/tutwuri.png" alt="Tutwuri" class="h-16" />
                        <div class="text-center">
                            <img src="/img/ramah.png" alt="Ramah" class="h-12 mx-auto" />
                            <img src="/img/bermutu.png" alt="Bermutu" class="h-12 mx-auto -mt-2" />
                        </div>
                    </div>
                </div>

                <h1 class="text-6xl md:text-8xl font-black text-blue-900 mb-6 leading-tight">
                    <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                        PKG
                    </span>
                    <br />
                    <span class="text-4xl md:text-6xl font-bold text-blue-700">Kecamatan Wagir</span>
                </h1>

                <p class="text-xl text-blue-800 mb-12 max-w-2xl mx-auto">
                    Pusat Kegiatan Guru Kecamatan Wagir - Platform terdepan untuk pengembangan pendidikan dan manajemen sekolah
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/login" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-4 rounded-full font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                        Mulai Sekarang
                    </a>
                    <a href="#applications" class="border-2 border-blue-600 text-blue-700 hover:bg-blue-600 hover:text-white px-8 py-4 rounded-full font-semibold text-lg transition-all">
                        Lihat Aplikasi
                    </a>
                </div>
            </div>

            <!-- Floating Elements -->
            <div class="absolute top-20 left-10 animate-bounce">
                <Icon icon="mdi:star" class="text-yellow-400 text-3xl opacity-60" />
            </div>
            <div class="absolute top-40 right-20 animate-pulse">
                <Icon icon="mdi:book-open" class="text-purple-300 text-4xl opacity-40" />
            </div>
            <div class="absolute bottom-20 left-20 animate-bounce" style="animation-delay: 1s">
                <Icon icon="mdi:school" class="text-pink-400 text-3xl opacity-50" />
            </div>
        </section>

        <!-- Applications Section -->
        <section id="applications" class="px-6 py-20 bg-white bg-opacity-50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-blue-900 mb-4">
                        Aplikasi <span class="text-blue-600">Terkait</span>
                    </h2>
                    <p class="text-blue-700 text-lg max-w-2xl mx-auto">
                        Akses berbagai platform pendidikan yang terintegrasi untuk mendukung kegiatan belajar mengajar
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Asesmen Card -->
                    <a href="https://asesmen.pkgwagir.or.id" target="_blank" class="group">
                        <el-card
                            :body-style="{
                                background: 'rgba(255, 255, 255, 0.9)',
                                backdropFilter: 'blur(16px)',
                                border: '1px solid rgba(59, 130, 246, 0.3)',
                                borderRadius: '1rem',
                                padding: '2rem',
                                transition: 'all 0.3s ease',
                                transform: 'scale(1)'
                            }"
                            shadow="hover"
                            class="hover:scale-105"
                        >
                            <template #header>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                        <Icon icon="mdi:clipboard-check" class="text-3xl text-white" />
                                    </div>
                                    <h3 class="text-xl font-bold text-blue-900 m-0">Asesmen</h3>
                                </div>
                            </template>
                            <div class="text-center">
                                <p class="text-blue-700 mb-4">Platform penilaian dan asesmen siswa yang komprehensif</p>
                                <div class="flex items-center justify-center text-blue-600 group-hover:text-blue-500">
                                    <span class="text-sm font-medium">Kunjungi Aplikasi</span>
                                    <Icon icon="mdi:arrow-right" class="ml-2 text-lg group-hover:translate-x-1 transition-transform" />
                                </div>
                            </div>
                        </el-card>
                    </a>

                    <!-- 7 KAIH Card -->
                    <a href="https://siswa.pkgwagir.or.id" target="_blank" class="group">
                        <el-card
                            :body-style="{
                                background: 'rgba(255, 255, 255, 0.9)',
                                backdropFilter: 'blur(16px)',
                                border: '1px solid rgba(59, 130, 246, 0.3)',
                                borderRadius: '1rem',
                                padding: '2rem',
                                transition: 'all 0.3s ease',
                                transform: 'scale(1)'
                            }"
                            shadow="hover"
                            class="hover:scale-105"
                        >
                            <template #header>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                        <Icon icon="mdi:book-open-page-variant" class="text-3xl text-white" />
                                    </div>
                                    <h3 class="text-xl font-bold text-blue-900 m-0">7 KAIH</h3>
                                </div>
                            </template>
                            <div class="text-center">
                                <p class="text-blue-700 mb-4">Program pengembangan karakter dan akhlak siswa</p>
                                <div class="flex items-center justify-center text-blue-600 group-hover:text-blue-500">
                                    <span class="text-sm font-medium">Kunjungi Aplikasi</span>
                                    <Icon icon="mdi:arrow-right" class="ml-2 text-lg group-hover:translate-x-1 transition-transform" />
                                </div>
                            </div>
                        </el-card>
                    </a>

                    <!-- Jurnal & Presensi Card -->
                    <a href="https://presensiswa.pkgwagir.or.id" target="_blank" class="group">
                        <el-card
                            :body-style="{
                                background: 'rgba(255, 255, 255, 0.9)',
                                backdropFilter: 'blur(16px)',
                                border: '1px solid rgba(59, 130, 246, 0.3)',
                                borderRadius: '1rem',
                                padding: '2rem',
                                transition: 'all 0.3s ease',
                                transform: 'scale(1)'
                            }"
                            shadow="hover"
                            class="hover:scale-105"
                        >
                            <template #header>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                        <Icon icon="mdi:calendar-check" class="text-3xl text-white" />
                                    </div>
                                    <h3 class="text-xl font-bold text-blue-900 m-0">Jurnal & Presensi</h3>
                                </div>
                            </template>
                            <div class="text-center">
                                <p class="text-blue-700 mb-4">Sistem jurnal mengajar dan presensi guru terintegrasi</p>
                                <div class="flex items-center justify-center text-blue-600 group-hover:text-blue-500">
                                    <span class="text-sm font-medium">Kunjungi Aplikasi</span>
                                    <Icon icon="mdi:arrow-right" class="ml-2 text-lg group-hover:translate-x-1 transition-transform" />
                                </div>
                            </div>
                        </el-card>
                    </a>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="px-6 py-16 bg-blue-50 bg-opacity-30">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-blue-900 text-center mb-8">Hubungi Kami</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white bg-opacity-80 backdrop-blur-xl rounded-2xl p-6 border border-blue-500 border-opacity-30 shadow-lg">
                        <h3 class="text-xl font-semibold text-blue-900 mb-4 flex items-center">
                            <Icon icon="mdi:globe" class="mr-3 text-blue-600" />
                            Website Resmi
                        </h3>
                        <a href="https://pkgwagir.or.id" class="text-blue-700 hover:text-blue-600 transition-colors font-medium">
                            https://pkgwagir.or.id
                        </a>
                    </div>

                    <div class="bg-white bg-opacity-80 backdrop-blur-xl rounded-2xl p-6 border border-blue-500 border-opacity-30 shadow-lg">
                        <h3 class="text-xl font-semibold text-blue-900 mb-4 flex items-center">
                            <Icon icon="mdi:youtube" class="mr-3 text-red-500" />
                            YouTube Channel
                        </h3>
                        <a href="https://youtube.com" target="_blank" class="text-blue-700 hover:text-blue-600 transition-colors font-medium">
                            Literasi Wagir Untuk Bangsa
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="px-6 py-8 bg-blue-100 border-t border-blue-300">
            <div class="max-w-7xl mx-auto text-center">
                <p class="text-blue-800">
                    © 2024 PKG Kecamatan Wagir. Dibuat dengan ❤️ untuk kemajuan pendidikan.
                </p>
            </div>
        </footer>
    </div>
</template>
