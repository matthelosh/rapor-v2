<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import DashLayout from '@/Layouts/DashLayout.vue'

const page = usePage()
</script>

<template>
    <Head title="Pengaturan peran dan ijin user" />
    <DashLayout>
        <template #header>Admin</template>
        <el-card :body-style="{background: pink,}">
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h3 class="flex items-center gap-1">
                        <Icon icon="mdi:account-settings-variant" />
                        <span class="font-bold text-slate-600 text-lg">Peran dan Ijin User</span>
                    </h3>
                </div>
                <div class="toolbar-items flex justify-end items-ceter">
                    
                </div>
            </template>
            <div class="card-body">
                <el-row>
                    <el-col>
                        <h3 class="text-lg font-bold text-sky-700">Peran dan Ijin</h3>
                        <el-table :data="page.props.roles">
                            <el-table-column label="#" type="index" />
                            <el-table-column label="Peran" prop="name"></el-table-column>
                            <el-table-column label="Ijin">
                                <template #default="scope">
                                    
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-col>
                </el-row>
            </div>
        </el-card>
    </DashLayout>
</template>