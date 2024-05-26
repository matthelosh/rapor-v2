<script setup>
import { ref, computed } from 'vue'

const props = defineProps({open: Boolean})
const emit = defineEmits(['close'])
const show = computed(() => props.open)
</script>

<template>
    <!-- <h1>Form Sekolah {{ props.open }}</h1> -->
    <el-dialog v-model="show" width="400" title="Formulir Impor Data Sekolah" @close="emit('close')">
    
    </el-dialog>
</template>