<script setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage()

</script>

<template>
    <div class="w-full h-full p-4 shadow bg-sky-50 border border-sky-300 rounded">
        <h3 class="text-sky-700 uppercase font-bold mb-3">Data Sekolah SD di Kecamatan Wagir</h3>
        <el-scrollbar max-height="420">
            <el-collapse type="primary">
                <template v-for="(sekolah, s) in page.props.sekolahs" :key="s">
                    <el-collapse-item>
                        <template #title>
                            <h3 class="pl-2 underline">{{ s+1 }}. {{ sekolah.nama }}</h3>
                        </template>
                        <div class="collapse-body p-4">
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
                    </el-collapse-item>
                </template>
            </el-collapse>
        </el-scrollbar>
    </div>
</template>