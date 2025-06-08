<script setup>
import { ref, computed } from 'vue'
import { usePage, Head, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import DashLayout from '@/Layouts/DashLayout.vue'

</script>

<template>
    <DashLayout>
        <Head title="Buku Induk" />
        <template #header>Buku Induk</template>
        <div>
            <el-card>
                <h1>Buku Induk Siswa</h1>
            </el-card>

        </div>
    </DashLayout>
</template>
