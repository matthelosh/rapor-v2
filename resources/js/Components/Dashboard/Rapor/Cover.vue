<script setup>
import { ref, computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import DashLayout from '@/Layouts/DashLayout.vue'

const page = usePage()
const props = defineProps({siswa: Object})

</script>

<template>
<Head title="Sampul Rapor" />

<DashLayout>
    <template #header>
        Sampul Rapor {{ siswa.nama }}
    </template>

    <div class="page">
        
    </div>
</DashLayout>

</template>