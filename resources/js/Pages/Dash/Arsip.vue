<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import DashLayout from '@/Layouts/DashLayout.vue'
import ArsipRapor from '@/Components/Dashboard/Rapor/ArsipRapor.vue'
const page = usePage()
const showArsip = ref(false)

const selectedRombel = ref({})
const selectedTapel = ref({})

const openArsip = (item, tapel) => {
    showArsip.value = true
    selectedRombel.value = item
    selectedTapel.value = tapel
}

const closeArsip = () => {
    showArsip.value = false
    selectedRombel.value = {}
}

</script>

<template>
<Head :title="`Arsip Rapor ${page.props.sekolahs[0].nama}`" />
<DashLayout>
    <template #header>Data Arsip</template>
    <el-card>
        <template #header>
            <div class="card-toolbar flex items-center justify-between">
                <span class="title uppercase flex items-center gap-1">
                    <Icon icon="mdi:archive" />
                    Arsip Rapor {{ page.props.sekolahs[0].nama }}
                </span>
            </div>
        </template>
        <div class="card-body">
            <el-collapse>
                <template v-for="(tapel, t) in page.props.tapels" :key="t">
                    <el-collapse-item>
                        <template #title>
                            <span class="title">Arsip Tahun {{ tapel.label }}</span>
                        </template>
                        <div class="collapse-body">
                            <el-table :data="tapel.rombels">
                                <el-table-column label="Kode" prop="kode"></el-table-column>
                                <el-table-column label="Kelas" prop="label"></el-table-column>
                                <el-table-column label="Opsi">
                                    <template #default="scope">
                                        <span>
                                            <el-button class="flex items-center gap-1" type="primary" @click="openArsip(scope.row, tapel)">
                                                <Icon icon="mdi:magnify-expand" />
                                                Lihat Arsip
                                            </el-button>
                                        </span>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </el-collapse-item>
                </template>
            </el-collapse>
        </div>
    </el-card>
</DashLayout>
<ArsipRapor :open="showArsip" :tapel="selectedTapel" :rombel="selectedRombel" @close="closeArsip" />
</template>