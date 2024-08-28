<script setup>
import { ref, computed } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { read, utils } from 'xlsx'

import DashLayout from '@/Layouts/DashLayout.vue';
import { ElNotification } from 'element-plus';

const props = defineProps({p5s: Array, proyeks: Array, rombel: Object})
const loading = ref(false)
const mode = ref('list')

</script>

<template>
<Head title="P5" />

<DashLayout>
    <template #header>
        Manajemen P5
    </template>
    <el-card class="body" v-loading="loading">
        <template #header>
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sky-800">Proyek Penguatan Profil Pelajar Pancasila (P5)</h3>
                <div class="flex items-center gap-2">
                </div>
            </div>
        </template>
        <el-collapse>
            <template v-for="(proyek,p) in props.proyeks" :key="p">
                <el-collapse-item :title="`${p+1}. ${proyek.nama} `">
                </el-collapse-item>
            </template>
        </el-collapse>

        {{ rombel }}
    </el-card>
</DashLayout>
</template>