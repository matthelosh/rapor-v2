<script setup>
import { Icon } from '@iconify/vue'
const emit = defineEmits([ 'close' ]);
</script>

<template>
    <el-card>
        <template #header>
            <div class="toolbar items-center justify-between">
                <h3>Form Impor Soal</h3>
                <button @click="emit('close')"><Icon icon="mdi:close" /></button>

            </div>
        </template>
        <div class="content p-4">
            <div class="text-center">
                Halo
            </div>
        </div>
    </el-card>
</template
