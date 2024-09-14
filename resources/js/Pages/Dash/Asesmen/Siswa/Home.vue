<script setup>
import { computed } from 'vue';
import { Head, usePage, router, Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import DashLayout from '@/Layouts/DashLayout.vue';

const page = usePage()

</script>

<template>
    <Head title="Asesmen" />
    <DashLayout>
        <template #header>
            Daftar Asesmen
        </template>

        <el-card>
            <h3 class="text-center">Data asesmen yang bisa kamu ikuti:</h3>
            <el-divider></el-divider>
                <template v-for="(asesmen, a) in page.props.asesmens">
                    <el-card class="mb-3" shadow="hover">
                        <div class="flex items-center justify-between">
                            <h3 class="uppercase w-full px-2">
                                <Link :href="route('asesmen.siswa.kerjakan', {_query: {kode: asesmen.kode, siswaId: page.props.auth.user.userable.nisn, prosesId: asesmen.proses[0]?.id}})" class="block">
                                {{ asesmen.nama }} <br> <small class="text-sky-500"> Proses ID: {{ asesmen.proses[0]?.id }}</small>
                                </Link>
                            </h3>
                        </div>

                    </el-card>
                </template>
                <!-- {{ page.props.asesmens }} -->
        </el-card>
        
    </DashLayout>

</template>