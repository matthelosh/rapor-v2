<script setup>
import { Head, Link } from '@inertiajs/vue3';

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
});

</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div
            class="relative min-h-screen flex flex-col items-center justify-start selection:bg-[#20a9ff] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3 border-slate-500 border-double border-b-8">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <img src="/img/tutwuri.png" alt="Tutwuri" class="h-28">
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Masuk
                            </Link>

                        </template>
                    </nav>
                </header>

                <main class="mt-6 text-slate-800">
                    <h3 class="text-xl font-bold">Aplikasi Rapor SD Kecamatan Wagir</h3>
                    <h3 class="text-lg font-bold mt-8">Pengolahan Hasil Asesmen untuk Rapor</h3>
                    <p class="mt-4">Pengolahan hasil asesmen dilakukan dengan memanfaatkan hasil formatif dan sumatif. Terdapat 2 jenis data, yaitu data hasil asesmen yang berupa angka (kuantitatif) serta data hasil asesmen yang berupa narasi (kualitatif)</p>
                    <p class="mt-4">Pengolahan hasil asesmen dalam bentuk angka (kuantitatif) didasarkan hanya pada hasil asesmen sumatif, sementara asesmen formatif sebagaimana diuraikan sebelumnya, berupa data atau informasi yang bersifat kualitatif, digunakan sebagai umpan balik untuk perbaikan pembelajaran sekaligus sebagai bahan pertimbangan menyusun deskripsi capaian kompetensi.</p>
                    <img src="/img/olah-nilai.png" alt="Opsi 3" class="mx-auto">
                    <p class="mt-4">Komponen rapor peserta didik SD/MI, SMP/MTs, SMA/MA, dan SMK/MAK atau sederajat minimal memuat informasi mengenai:</p>
                    <ol class="list-decimal pl-8">
                        <li>Identitas peserta didik</li>
                        <li>Nama Satuan Pendidikan</li>
                        <li>Kelas</li>
                        <li>Semester</li>
                        <li>Mata Pelajaran</li>
                        <li>Nilai</li>
                        <li>Deskripsi</li>
                        <li>Catatan Guru</li>
                        <li>Presensi, dan</li>
                        <li>Kegiatan Ekstrakurikuler</li>
                    </ol>
                    <p class="mt-4">
                        Format rapor ini menggunakan opsi 3. Yaitu, deskripsi diambil dari poin-poin penting dari  materi yang sudah diberikan.
                        <img src="/img/opsi-3.png" alt="Opsi 3" class=mx-auto>
                    </p>
                    <h3 class="text-lg font-bold mt-8">Format Rapor</h3>
                    <p class="mt-4">
                        <img src="/img/format-rapor.png" alt="Format Rapor" class="mx-auto">
                    </p>
                    <p class="mt-4">*) <a href="https://kurikulum.kemdikbud.go.id/wp-content/uploads/2022/06/Panduan-Pembelajarn-dan-Asesmen.pdf" target="_blank" class="text-sky-500">Panduan Pembelajaran dan Asesmen Kemdikbud Tahun 2022</a></p>
                </main>

            </div>
        </div>
        <footer class="py-6 text-center text-sm text-black dark:text-white/70 bg-sky-100 w-full">
            &copy; {{ new Date().getFullYear() }} <a href="mailto:matthelosh@gmail.com" class="text-sky-800 font-bold">Matsoleh</a>
        </footer>
    </div>
</template>
