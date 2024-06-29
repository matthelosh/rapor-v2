<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import RaporPAS from './RaporPAS.vue';
const page = usePage()

const props = defineProps({ open: Boolean, rombel: Object, tapel: Object })
const emit = defineEmits(['close'])

const cetakRapor = ref(false)
const selectedSiswa = ref({})
const semester = ref('')
const openRapor = (siswa, sem) => {
    cetakRapor.value = true
    selectedSiswa.value = siswa
    semester.value = sem
}

const closeCetak = () => {
    cetakRapor.value = false
    selectedSiswa.value = {}
    semester.value = ''
}

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
            <el-table :data="rombel.siswas" height="90vh">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="NISN" prop="nisn"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <el-table-column label="JK" prop="jk"></el-table-column>
                <el-table-column label="Agama" prop="agama"></el-table-column>
                <el-table-column label="Cetak Rapor">
                    <template #default="scope">
                        <el-button-group>
                            <el-button v-for="sem in ['1','2']" :key="sem" type="primary" @click="openRapor(scope.row, sem)">Sem {{ sem }}</el-button>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <el-dialog v-model="cetakRapor" :show-close="false" top="5vh" width="80vw">
            <template #header>
                <span class="flex items-start justify-between">
                    <h3 class="title font-bold text-lg">Cetak Rapor PAS</h3>
                    <el-button type="danger" @click="closeCetak" size="small">
                        <Icon icon="mdi:close" />
                    </el-button>
                </span>
            </template>
            <RaporPAS :siswa="selectedSiswa" :rombel="props.rombel" :tapel="props.tapel" :semester="semester" @close="closeCetak" :arsip="true" />
        </el-dialog>
    </el-dialog>
</template>
