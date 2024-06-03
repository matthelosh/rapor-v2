<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import axios from 'axios'
import { read, utils } from 'xlsx'
import { ElNotification } from 'element-plus';

const props = defineProps({show: Boolean, selectedRombel: Object})
const emit = defineEmits(['close', 'refresh'])

const members = ref([])
const searchMember  = ref('')
const selectedMembers = ref([])
const nonMembers = ref([])
const searchNonMember = ref('')
const fitleredMembers = computed(() => {
    return members.value.filter(data => !searchMember.value || data.nama.toLowerCase().includes(searchMember.value.toLowerCase()))
})
const filteredNonMembers = computed(() => {
    return nonMembers.value.filter(data => !searchNonMember.value || data.nama.toLowerCase().includes(searchNonMember.value.toLowerCase()))
})
const selectedNonMembers = ref([])

const selectionNonMember = (val) => {
    selectedNonMembers.value = val
}

const selectionMember = (val) => {
    selectedMembers.value = val
}

const assignMember = async() => {
    await router.post(route('dashboard.rombel.member.assign', {id: props.selectedRombel.id}), {siswas: selectedNonMembers.value}, {
        onSuccess: (page) => {
            emit('refresh')
            selectedNonMembers.value.forEach(siswa => members.value.push(siswa))
            getNonMember()

        }
    })
}

const getNonMember = async() => {
    await axios.post(route('dashboard.siswa.nonmember', {_query: {rombelId: props.selectedRombel.id}}))
                .then(res => {
                    nonMembers.value = res.data.siswas
                })
                .catch(err => console.log(err))
}

onMounted(() => {
    getNonMember()
    members.value = props.selectedRombel.siswas
})

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
                                <div class="flex items-center justify-between">
                                    <div class="card-title flex items-center">
                                    <Icon icon="mdi:human-child" class="mb-1 text-lg" />
                                    Data Peserta Didik {{ props.selectedRombel.label }}
                                    </div>
                                    <div class="card-toolbar flex">
                                        <el-button type="danger" size="small" v-if="selectedMembers.length > 0">Keluarkan</el-button>
                                    </div>
                                </div>
                                <el-input v-model="searchMember" placeholder="Cari Siswa" class="mt-2" />
                            </template>
                            <div class="card-body">
                                <el-table :data="fitleredMembers" @selection-change="selectionMember" height="65vh">
                                    <el-table-column type="selection" width="55" />
                                    <el-table-column label="NISN" prop="nisn" />
                                    <el-table-column label="Nama" prop="nama" />
                                    <el-table-column label="JK" prop="jk" />
                                </el-table>
                            </div>
                        </el-card>
                    </el-col>
                    <el-col :span="12">
                        <el-card>
                            <template #header>
                                <div class="flex items-center justify-between">
                                    <div class="card-title flex items-center">
                                    <Icon icon="mdi:human-child" class="mb-1 text-lg" />
                                    Siswa belum masuk Rombel
                                    </div>
                                    <div class="card-toolbar flex">
                                        <el-button type="primary" size="small" v-if="selectedNonMembers.length > 0" @click="assignMember">Masukkan</el-button>
                                    </div>
                                </div>
                                <el-input v-model="searchNonMember" placeholder="Cari Siswa" class="mt-2" />
                            </template>
                            <div class="card-body">
                                <el-table :data="filteredNonMembers" @selection-change="selectionNonMember" height="65vh">
                                    <el-table-column type="selection" width="55" />
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