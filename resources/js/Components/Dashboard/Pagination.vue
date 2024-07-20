<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
const props = defineProps({data: Object, dataName: String})

const changePage = (e) => {
    router.visit(e.url, {only: [props.dataName], preserveState: true})
}
</script>

<template>
    <div class="flex items-center justify-between gap-4">
        <!-- {{ data.links }} -->
          
          <el-button-group>
            <template v-for="(link, l) in props.data.links" :key="l">
                <el-button @click="changePage(link)" :type="props.data.current_page == link.label ? 'primary': ''" v-html="link.label" class="flex border py-1 px-2 min-w-[30px] justify-center" :disabled="link.url === null"></el-button>
            </template>
        </el-button-group>
        <h3 class="text-sky-700">Jumlah: {{ data.total }}</h3>
    </div>
</template>