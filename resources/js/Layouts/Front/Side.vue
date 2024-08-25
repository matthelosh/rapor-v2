<script setup>
import { ref, defineAsyncComponent } from 'vue'
import { router, Link } from '@inertiajs/vue3';

const Agenda = defineAsyncComponent(() => import('@/Components/Front/Side/Agenda.vue'))
const search = ref('')
defineProps({
    infos: Array,
    agendas: Array
})

const searchPost = () => {
    router.visit(route('front.post.search', { _query: {q: search.value}}))
}
</script>

<template>
<div>
    <el-input placeholder="Cari Tulisan" v-model="search" >
        <template #suffix>
            <el-button text size="small" @click="searchPost">
                <Icon icon="mdi:magnify" />
            </el-button>
        </template>
    </el-input>
    <el-divider>Pengumuman</el-divider>
    <ol class="pl-6">
        <li v-for="(info, i) in infos" :key="i" class="list-disc">
            <Link :href="`/baca/${info.slug}`" class="hover:underline">
                {{ info.title }}
            </Link>
        </li>
    </ol>
    <el-divider>Agenda</el-divider>
    <Agenda :agendas="agendas" />
</div>
</template>