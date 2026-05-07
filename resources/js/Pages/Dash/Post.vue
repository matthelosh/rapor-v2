<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

import dayjs from 'dayjs'
import 'dayjs/locale/id'

dayjs.locale('id')

import DashLayout from '@/Layouts/DashLayout.vue';
const WritePost = defineAsyncComponent(() => import('@/Components/Dashboard/Post/WritePost.vue'))
const selectedPost = ref(null)
const mode = ref('list')

const props = defineProps({posts: Object})

const currentPage = computed(() => props.posts.current_page)
const currentChange = (num) => {
    // currentPage.value = num
    router.visit(props.posts.path+'?page='+num, {preserveState:true})
}

const newPost = () => {
    mode.value = 'write'
}

const edit = (item) => {
    selectedPost.value = item
    mode.value = 'write'
}

const closeWrite = () => {
    selectedPost.value = null
    mode.value = 'list'
}
</script>

<template>
<Head title="Berita" />

<DashLayout>
    <template #header>
        Data Berita
    </template>
    <el-card v-if="mode == 'list'">
        <template #header>
            <div class="toolbar flex items-center justify-between">
                <h3>Daftar Tulisan</h3>
                <div class="toolbar-items flexx items-center gap-2">
                    <el-button type="primary" size="small" @click="newPost">Tulis Baru</el-button>
                </div>
            </div>
        </template>
        <template #default>
            <el-table :data="props.posts.data" max-height="76vh">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="Judul">
                    <template #default="scope">
                        <el-button text type="primary" @click="edit(scope.row)">{{ scope.row.title }}</el-button>
                    </template>
                </el-table-column>
                <el-table-column label="Penulis" width="200">
                    <template #default="scope">
                        {{ scope.row.author.name }}
                    </template>
                </el-table-column>
                <el-table-column label="Ditulis" width="200">
                    <template #default="scope">
                        {{ dayjs(scope.row.created_at).locale('id').format('DD/MM/YYYY HH:m:s')}}
                    </template>
                </el-table-column>
                <el-table-column label="Diperbarui" width="200">
                    <template #default="scope">
                        {{ dayjs(scope.row.updated_at).locale('id').format('DD/MM/YYYY HH:m:s')}}
                    </template>
                </el-table-column>
                <el-table-column label="Status" width="200" fixed="right">
                    <template #default="scope">
                        <el-select v-model="scope.row.status">
                            <el-option v-for="(stat, s) in ['draft', 'published', 'down']" :key="s" :value="stat"></el-option>
                        </el-select>
                    </template>
                </el-table-column>
                <el-table-column label="Opsi" width="100" fixed="right">
                    <template #default="scope">
                        <div class="flex items-center gap-1">
                            <el-button type="danger" circle size="small">
                                <Icon icon="mdi:delete" />
                            </el-button>
                        </div>
                    </template>
                </el-table-column>
                
            </el-table>
        </template>
        <template #footer>
            <div class="flex items-center justify-between">
                <div></div>
                <el-pagination 
                    :page-count="props.posts.last_page"
                    :current-page="currentPage" 
                    :total="props.posts.total" 
                    layout="prev, pager, next, total" 
                    background 
                    @current-change="currentChange">
                </el-pagination>
            </div>
        </template>
    </el-card>
    <WritePost v-if="mode == 'write'" :selectedPost="selectedPost" @close="closeWrite" />
</DashLayout>
</template>