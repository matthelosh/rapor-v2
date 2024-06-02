<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import axios from 'axios'
import { read, utils } from 'xlsx'
import { ElNotification } from 'element-plus';

const props = defineProps({show: Boolean, selectedRombel: Object})
const emit = defineEmits(['close'])

const members = computed(() => {
    props.selectedRombel.siswas
})

const nonMembers = ref([])

</script>

<template>
    <el-dialog v-model="props.show" fullscreen @close="emit('close')">
        <template #header >
            <div class="flex items-center justify-between">
                <div class="dialog-title">Manajemen Peserta Didik {{ props.selectedRombel.label }}</div>
            </div>
            
        </template>
        <el-scrollbar height="90vh">
            <div class="dialog-body bg-slate-100 py-2">
                <el-row :gutter="12">
                    <el-col :span="12">
                        <el-card>
                            <template #header>
                                <span class="flex items-center">
                                    <Icon icon="mdi:human-child" class="mb-1 text-lg" />
                                    Data Peserta
                                </span>
                            </template>
                            <div class="card-body">
                                <el-table :data="members">
                                    <el-table-column label="NISN" prop="nisn" />
                                    <el-table-column label="Nama" prop="nama" />
                                    <el-table-column label="JK" prop="jk" />
                                    <el-table-column label="Opsi">
                                        <template #default="scope">
                                            <el-tooltip :content="`Keluarkan ${scope.row.nama}?`" effect="customize">
                                                <el-button circle type="danger">
                                                    <Icon icon="mdi:close" />
                                                </el-button>
                                            </el-tooltip>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </el-card>
                    </el-col>
                    <el-col :span="12">
                        <el-card>
                            <template #header>
                                <span class="flex items-center">
                                    <Icon icon="mdi:human-child" class="mb-1 text-lg" />
                                    Siswa belum masuk Rombel
                                </span>
                            </template>
                            <div class="card-body">
                                <el-table :data="nonMembers">
                                    <el-table-column label="NISN" prop="nisn" />
                                    <el-table-column label="Nama" prop="nama" />
                                    <el-table-column label="JK" prop="jk" />
                                </el-table>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>
            </div>
        </el-scrollbar>
    </el-dialog>
</template>