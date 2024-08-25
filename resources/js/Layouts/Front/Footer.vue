<script setup>
import { Icon } from '@iconify/vue';
import { ref, computed } from 'vue'

const tahun = computed(() => {
    const date = new Date()
    return date.getFullYear()
})
</script>

<template>
<footer class="bg-sky-500 w-screen">
    <el-backtop :right="100" :bottom="100"></el-backtop>
    <div class="footer p-4 flex justify-between items-center">
        <p class="text-white">
            &copy; {{ tahun }} 
            <a href="mailto:matthelosh@gmail.com" target="_blank" class="text-sky-100 hover:underline hover:text-sky-50">TIM IT PKG Kec. Wagir</a>
        </p>
        <a href="https://github.com/matthelosh/rapor-v2" target="_blank">
            <Icon icon="mdi:github" class="text-2xl text-white" />
        </a>
    </div>
</footer>
</template>