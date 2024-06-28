<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

const page = usePage()

const props = defineProps({ open: Boolean, rombel: Object, tapel: Object })
const emit = defineEmits(['close'])

onBeforeMount(async() => {

})
</script>

<template>
    <el-dialog v-model="props.open" fullscreen :show-close="false">
        <template #header>
            <div class="dialog-toolbar flex items-center justify-between border-b py-2">
                <span class="title">Lihat Arsip {{ props.rombel.label }} Tahun Pelajaran {{ props.tapel.label }}</span>
                <el-button type="danger" @click="emit('close')" size="small">
                    <Icon icon="mdi:close" />
                </el-button>
            </div>
        </template>
        <div class="dialog-body bg-slate-200">
            <h1>Halo</h1>
            <p>{{ props.rombel.siswas }}</p>
        </div>
    </el-dialog>
</template>
