<script setup>
import { Head } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import Header from "@/Layouts/Front/Header.vue";
import Footer from "@/Layouts/Front/Footer.vue";
</script>

<template>
    <Head title="Sugeng Rawuh di PKG Wagir" />

    <div class="min-h-screen bg-base-200">
        <Header />

        <!-- HERO SECTION - More Impact -->
        <section class="hero min-h-screen relative overflow-hidden">
            <!-- Background Gradient + Overlay -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-primary/10 via-base-200 to-secondary/10"
            ></div>

            <div class="hero-content text-center px-6 py-20 relative z-10">
                <div class="max-w-4xl mx-auto">
                    <!-- Logo Group -->
                    <div class="flex flex-col items-center gap-6 mb-10">
                        <img
                            src="/img/malangkab.png"
                            alt="Kabupaten Malang"
                            class="h-24 mx-auto drop-shadow-lg"
                        />
                        <div class="flex items-center gap-6">
                            <img
                                src="/img/tutwuri.png"
                                alt="Tutwuri"
                                class="h-20"
                            />
                            <div class="flex flex-col items-center -space-y-1">
                                <img
                                    src="/img/ramah.png"
                                    alt="Ramah"
                                    class="h-14"
                                />
                                <img
                                    src="/img/bermutu.png"
                                    alt="Bermutu"
                                    class="h-14"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Main Title -->
                    <h1
                        class="text-6xl md:text-7xl lg:text-8xl font-black tracking-tighter mb-4"
                    >
                        <span> PKG </span>
                        <br />
                        <span
                            class="text-4xl md:text-5xl lg:text-6xl font-bold text-base-content"
                        >
                            Kecamatan Wagir
                        </span>
                    </h1>

                    <p
                        class="text-xl md:text-2xl text-base-content/80 max-w-2xl mx-auto leading-relaxed mb-12"
                    >
                        Pusat Kegiatan Guru yang mendukung transformasi
                        pendidikan berkualitas di Kecamatan Wagir
                    </p>

                    <!-- CTA -->
                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-center items-center"
                    >
                        <a
                            href="/login"
                            class="btn btn-primary btn-lg rounded-2xl px-10 text-lg font-semibold shadow-xl shadow-primary/30 hover:scale-105 transition-transform"
                        >
                            Mulai Sekarang
                        </a>
                        <a
                            href="#applications"
                            class="btn btn-outline btn-lg rounded-2xl px-10 text-lg font-semibold border-2 hover:bg-primary hover:text-primary-content transition-all"
                        >
                            Jelajahi Aplikasi
                        </a>
                    </div>

                    <!-- Trust Signal -->
                    <div
                        class="mt-16 flex justify-center items-center gap-8 text-sm opacity-75"
                    >
                        <div class="flex items-center gap-2">
                            <Icon
                                icon="mdi:shield-check"
                                class="text-2xl text-success"
                            />
                            <span>Terintegrasi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Icon
                                icon="mdi:school"
                                class="text-2xl text-primary"
                            />
                            <span>Berbasis Sekolah</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <Icon
                                icon="mdi:users"
                                class="text-2xl text-secondary"
                            />
                            <span>Untuk Guru & Siswa</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div
                class="absolute bottom-10 left-1/2 -translate-x-1/2 hidden md:flex flex-col items-center gap-1 text-base-content/40"
            >
                <span class="text-xs tracking-widest">SCROLL</span>
                <Icon icon="mdi:chevron-down" class="text-3xl animate-bounce" />
            </div>
        </section>

        <!-- APPLICATIONS SECTION -->
        <section id="applications" class="py-24 bg-base-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <div
                        class="inline-flex items-center gap-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-4"
                    >
                        <Icon icon="mdi:apps" />
                        PLATFORM TERINTEGRASI
                    </div>
                    <h2 class="text-5xl font-bold tracking-tight mb-4">
                        Aplikasi <span class="text-primary">Unggulan</span>
                    </h2>
                    <p class="text-lg text-base-content/70 max-w-xl mx-auto">
                        Semua kebutuhan pendidikan dalam satu ekosistem digital
                        yang mudah digunakan
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <a
                        href="https://asesmen.pkgwagir.or.id"
                        target="_blank"
                        class="group"
                    >
                        <div
                            class="card bg-gradient-to-br from-base-100 to-base-200 border border-base-300 hover:border-primary h-full transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl"
                        >
                            <div
                                class="card-body items-center text-center pt-10"
                            >
                                <div
                                    class="w-20 h-20 bg-primary/10 rounded-3xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
                                >
                                    <Icon
                                        icon="mdi:clipboard-check"
                                        class="text-5xl text-primary"
                                    />
                                </div>
                                <h3 class="card-title text-2xl text-primary">
                                    Asesmen
                                </h3>
                                <p class="text-base-content/70 leading-relaxed">
                                    Penilaian siswa yang komprehensif, analisis
                                    data, dan pelaporan otomatis
                                </p>
                                <div class="mt-auto pt-8">
                                    <span
                                        class="text-primary font-medium flex items-center justify-center gap-2 group-hover:gap-3 transition-all"
                                    >
                                        Buka Aplikasi
                                        <Icon
                                            icon="mdi:arrow-right"
                                            class="text-xl"
                                        />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Card 2 -->
                    <a
                        href="https://siswa.pkgwagir.or.id"
                        target="_blank"
                        class="group"
                    >
                        <div
                            class="card bg-gradient-to-br from-base-100 to-base-200 border border-base-300 hover:border-secondary h-full transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl"
                        >
                            <div
                                class="card-body items-center text-center pt-10"
                            >
                                <div
                                    class="w-20 h-20 bg-secondary/10 rounded-3xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
                                >
                                    <Icon
                                        icon="mdi:book-open-page-variant"
                                        class="text-5xl text-secondary"
                                    />
                                </div>
                                <h3 class="card-title text-2xl text-primary">
                                    7 KAIH
                                </h3>
                                <p class="text-base-content/70 leading-relaxed">
                                    Program penguatan karakter dan akhlak mulia
                                    siswa
                                </p>
                                <div class="mt-auto pt-8">
                                    <span
                                        class="text-secondary font-medium flex items-center justify-center gap-2 group-hover:gap-3 transition-all"
                                    >
                                        Buka Aplikasi
                                        <Icon
                                            icon="mdi:arrow-right"
                                            class="text-xl"
                                        />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Card 3 -->
                    <a
                        href="https://presensiswa.pkgwagir.or.id"
                        target="_blank"
                        class="group"
                    >
                        <div
                            class="card bg-gradient-to-br from-base-100 to-base-200 border border-base-300 hover:border-accent h-full transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl"
                        >
                            <div
                                class="card-body items-center text-center pt-10"
                            >
                                <div
                                    class="w-20 h-20 bg-accent/10 rounded-3xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"
                                >
                                    <Icon
                                        icon="mdi:calendar-check"
                                        class="text-5xl text-accent"
                                    />
                                </div>
                                <h3 class="card-title text-2xl text-primary">
                                    Jurnal & Presensi
                                </h3>
                                <p class="text-base-content/70 leading-relaxed">
                                    Manajemen jurnal mengajar dan presensi
                                    digital terintegrasi
                                </p>
                                <div class="mt-auto pt-8">
                                    <span
                                        class="text-accent font-medium flex items-center justify-center gap-2 group-hover:gap-3 transition-all"
                                    >
                                        Buka Aplikasi
                                        <Icon
                                            icon="mdi:arrow-right"
                                            class="text-xl"
                                        />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <!-- CONTACT / LINKS -->
        <section class="py-20 bg-base-300/50">
            <div class="max-w-4xl mx-auto px-6">
                <h2 class="text-4xl font-bold text-center mb-12 text-primary">
                    Hubungi & Ikuti Kami
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a
                        href="https://pkgwagir.or.id"
                        target="_blank"
                        class="group card bg-base-100 hover:bg-white border border-base-300 hover:border-primary transition-all p-8 rounded-3xl"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-3xl"
                            >
                                🌐
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">
                                    Website Resmi
                                </h3>
                                <p
                                    class="text-primary font-medium group-hover:underline"
                                >
                                    pkgwagir.or.id
                                </p>
                            </div>
                        </div>
                    </a>

                    <a
                        href="https://youtube.com"
                        target="_blank"
                        class="group card bg-base-100 hover:bg-white border border-base-300 hover:border-error transition-all p-8 rounded-3xl"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-error/10 rounded-2xl flex items-center justify-center text-3xl"
                            >
                                📺
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">
                                    YouTube Channel
                                </h3>
                                <p
                                    class="text-error font-medium group-hover:underline"
                                >
                                    Literasi Wagir Untuk Bangsa
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <Footer />
    </div>
</template>
