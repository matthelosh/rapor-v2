<script setup>
import { ref, computed } from 'vue'
import { Icon } from '@iconify/vue'
const props = defineProps({url: String, show: Boolean})
const emit = defineEmits(['close'])
</script>

<template>
    <el-dialog v-model="props.show" :show-close="false">
        <template #header>
            <div class="toolbar flex items-start justify-between">
                <span class="title text-lg font-bold text-slate-600">
                    Video tutorial
                </span>
                <el-button type="danger" @click="emit('close')" size="small">
                    <Icon icon="mdi:close" />
                </el-button>
            </div>
        </template>
        <div class="dialog-bo">
            <video :src="props.url" controls></video>
        </div>
    </el-dialog>
</template>