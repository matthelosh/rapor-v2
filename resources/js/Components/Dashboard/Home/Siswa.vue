<script setup>
import { ref, computed } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { fotoSiswa } from '@/helpers/Gambar';

const page = usePage()
</script>

<template>
    <div class="h-[86vh] w-full bg-sky-50 p-2 flex items-center justify-center">
        <div>
            <div class="flex justify-center pb-8">
                <el-avatar :src="page.props.auth.user.userable.foto" :size="150">
                    <template #error>
                        <img :src="fotoSiswa(page.props.auth.user.userable)" alt="">
                    </template>
                </el-avatar>
            </div>
            <h3 class="text-center text-lg">Selamat Datang</h3>
            <h3 class="text-center text-2xl uppercase font-bold text-sky-800">Ananda {{ page.props.auth.user.userable.nama }}</h3>
            <div class="flex items-center justify-center gap-4 mt-8">
                <Link :href="route('asesmen.siswa')" class="fle text-orange-500 hover:-translate-y-4 transition-all ease-in-out hover:drop-shadow-lg">
                    <Icon icon="mdi:book-edit" class="text-center mx-auto text-8xl" />
                    <p class="text-center">Asesmen</p>
                </Link>
                <Link href="#" class="fle text-sky-800 hover:-translate-y-4 transition-all ease-in-out hover:drop-shadow-lg">
                    <Icon icon="mdi:book-education" class="text-center mx-auto text-8xl" />
                    <p class="text-center">Rapor</p>
                </Link>
            </div>
        </div>
    </div>
</template>