<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DashLayout from '@/Layouts/DashLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ElCard } from 'element-plus'
import { Icon } from '@iconify/vue'

const page = usePage()

const sekolahs = page.props.sekolahs
</script>

<template>
    <Head title="Dashboard" />

    <DashLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="page">
            <el-row :gutter="8" class="mb-2">
                <el-col :span="12" >
                    <el-card class="max-h-[300px] h-[300px]">
                        <template #header>
                            <div class="header flex items-center">
                                <Icon icon="mdi:city-variant" class="mb-1 text-2xl" />
                                <h3 class="text-lg font-bold text-slate-700">Data Sekolah</h3>
                            </div>
                        </template>
                        <div class="card-body">
                            <el-table :data="sekolahs" max-height="250">
                                <el-table-column prop="npsn" label="NPSN" />
                                <el-table-column prop="nama" label="Nama" />
                                <el-table-column prop="alamat" label="Alamat" />
                                <el-table-column prop="nama_ks" label="Kepala Sekolah" />
                            </el-table>
                        </div>
                    </el-card>
                </el-col>
                <el-col :span="6">
                    <el-card class="max-h-[300px] h-[300px]"></el-card>
                </el-col>
                <el-col :span="6">
                    <el-card class="max-h-[300px] h-[300px]"></el-card>
                </el-col>
            </el-row>
            <el-row :gutter="8" class="mb-2">
                <el-col :span="6">
                    <el-card class="max-h-[300px] h-[300px]"></el-card>
                </el-col>
                <el-col :span="6">
                    <el-card class="max-h-[300px] h-[300px]"></el-card>
                </el-col>
                <el-col :span="6">
                    <el-card class="max-h-[300px] h-[300px]"></el-card>
                </el-col>
                <el-col :span="6">
                    <el-card class="max-h-[300px] h-[300px]"></el-card>
                </el-col>
            </el-row>
        </div>
    </DashLayout>
</template>
