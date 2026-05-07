<script setup>
import { ref, computed } from 'vue'

const props = defineProps({ galeris: Array})
</script>

<template>
<el-carousel indicator-position="none" motion-blur>
    <el-carousel-item v-for="(galeri, g) in props.galeris" :key="g">
        <h3 class="absolute z-30 bottom-8 w-[80%] left-[8%] text-white drop-shadow-lg text-lg">{{ galeri.nama }}</h3>
        <el-image :src="galeri.url"></el-image>
    </el-carousel-item>
</el-carousel>
</template>