<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import DashLayout from '@/Layouts/DashLayout.vue'

const page = usePage()
const role = computed(() => page.props.auth.roles[0])

const cadangkan = async() => {
    const sekolahId = role == 'ops' ? page.props.sekolahs[0].npsn : ''
    router.post(route('dashboard.backup.store'), {sekolahId: sekolahId}, {
        onSuccess: page => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500);
            })
        }
    })
}
</script>

<template>
    <Head title="Pengaturan Bakcup dan Restore Data" />
    <DashLayout>
        <template #header>Admin</template>
        <el-card >
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h3 class="flex items-center gap-1">
                        <Icon icon="mdi:hdd" />
                        <span class="font-bold text-slate-600 text-lg">Backup & Restore</span>
                    </h3>
                    <div class="toolbar-items flex justify-end items-ceter">
                        <el-button type="primary" @click="cadangkan">Cadangkan</el-button>
                    </div>
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