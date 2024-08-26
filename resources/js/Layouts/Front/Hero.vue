<script setup>
import { usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
const page = usePage()

</script>

<template>
    <div class="h-[500px] w-full bg-sky-50 mb-4 flex items-center justify-center overflow-hidden caompaign" :class="`bg-[url('/img/logo-hutri-79.webp')] bg-no-repeat bg-cover`">
        <!-- <el-carousel height="500px" motion-blur>
            <el-carousel-item v-for="item in 4" :key="item">
                <h3 class="small justify-center" text="2xl">{{ item }}</h3>
            </el-carousel-item>
        </el-carousel> -->
        <!-- <el-image src="/img/logo-hutri-79.webp" style="width: 100%;"></el-image> -->
    </div>
    <div class="w-full h-full p-4 shadow bg-sky-50 border border-sky-300 rounded">
        <h3 class="text-sky-700 uppercase font-bold mb-3">Data Sekolah SD di Kecamatan Wagir</h3>
        <el-scrollbar max-height="420">
            <el-collapse type="primary">
                <template v-for="(sekolah, s) in page.props.sekolahs" :key="s">
                    <el-collapse-item>
                        <template #title>
                            <h3 class="pl-2 text-sky-700">{{ s+1 }}. {{ sekolah.nama }}</h3>
                        </template>
                        <div class="collapse-body p-4 hidden md:block">
                            <h3 class="font-bold text-sky-700">Data Guru</h3>
                            <table class="border">
                                <thead class="bg-sky-50">
                                    <tr>
                                        <th rowspan="2" class="px-4 border py-1">Jumlah</th>
                                        <th class="px-2 border py-1" colspan="6">Agama</th>
                                    </tr>
                                    <tr>
                                        <th class="px-2 border py-1" v-for="agama in ['Islam', 'Kristen', 'Katolik','Hindu', 'Budha', 'Konghuchu']">{{ agama }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.gurus.length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.gurus.filter(guru => guru.agama == 'Islam').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.gurus.filter(guru => guru.agama == 'Kristen').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.gurus.filter(guru => guru.agama == 'Katolik').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.gurus.filter(guru => guru.agama == 'Hindu').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.gurus.filter(guru => guru.agama == 'Budha').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.gurus.filter(guru => guru.agama == 'Konghuchu').length }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h3 class="font-bold text-sky-700 mt-4">Data Siswa</h3>
                            <table class="border">
                                <thead class="bg-sky-50">
                                    <tr>
                                        <th rowspan="2" class="px-4 border py-1">Jumlah</th>
                                        <th class="px-2 border py-1" colspan="6">Agama</th>
                                    </tr>
                                    <tr>
                                        <th class="px-2 border py-1" v-for="agama in ['Islam', 'Kristen', 'Katolik','Hindu', 'Budha', 'Konghuchu']">{{ agama }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.siswas.length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.siswas.filter(guru => guru.agama == 'Islam').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.siswas.filter(guru => guru.agama == 'Kristen').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.siswas.filter(guru => guru.agama == 'Katolik').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.siswas.filter(guru => guru.agama == 'Hindu').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.siswas.filter(guru => guru.agama == 'Budha').length }}
                                        </td>
                                        <td class="border py-1 text-center">
                                            {{ sekolah.siswas.filter(guru => guru.agama == 'Konghuchu').length }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="md:hidden">
                            <h3 class="font-bold text-sky-700 pl-4">Data Guru</h3>
                            <table class="mx-4 w-[90%]">
                                <tr>
                                    <td class="border-y w-20">Jumlah</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.gurus.length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Islam</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.gurus.filter(guru => guru.agama == 'Islam').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Kristen</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.gurus.filter(guru => guru.agama == 'Kristen').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Katolik</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.gurus.filter(guru => guru.agama == 'Katolik').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Hindu</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.gurus.filter(guru => guru.agama == 'Hindu').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Budha</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.gurus.filter(guru => guru.agama == 'Budha').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Konghuchu</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.gurus.filter(guru => guru.agama == 'Konghuchu').length }}</td>
                                </tr>
                            </table>
                            <h3 class="font-bold text-sky-700 pl-4 mt-6">Data Siswa</h3>
                            <table class="mx-4 w-[90%]">
                                <tr>
                                    <td class="border-y w-20">Jumlah</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.siswas.length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Islam</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.siswas.filter(siswa => siswa.agama == 'Islam').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Kristen</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.siswas.filter(siswa => siswa.agama == 'Kristen').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Katolik</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.siswas.filter(siswa => siswa.agama == 'Katolik').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Hindu</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.siswas.filter(siswa => siswa.agama == 'Hindu').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Budha</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.siswas.filter(siswa => siswa.agama == 'Budha').length }}</td>
                                </tr>
                                <tr>
                                    <td class="border-y w-20">Konghuchu</td>
                                    <td class="border-y w-2">:</td>
                                    <td class="border-y">{{ sekolah.siswas.filter(siswa => siswa.agama == 'Konghuchu').length }}</td>
                                </tr>
                            </table>
                        </div>
                    </el-collapse-item>
                </template>
            </el-collapse>
        </el-scrollbar>
    </div>
</template>