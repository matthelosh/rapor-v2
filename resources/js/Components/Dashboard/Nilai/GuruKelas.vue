<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { ElCard } from 'element-plus'
const page = usePage()

const FormNilaiHarian = defineAsyncComponent(() => import('@/Components/Dashboard/Nilai/FormNilaiHarian.vue'))
const FormNilaiTS = defineAsyncComponent(() => import('@/Components/Dashboard/Nilai/FormNilaiTS.vue'))
const FormNilaiAS = defineAsyncComponent(() => import('@/Components/Dashboard/Nilai/FormNilaiAS.vue'))

const mode = ref('home')
const selectedRombel = ref({})
const selectedMapel = ref({})

const guruKelas = () => {
    return page.props.auth.roles[0].includes('guru_kelas')
}

const openForm = (mapel, rombel, komponen) => {
    // console.log(rombel, mapel, komponen)
    selectedRombel.value = rombel
    selectedMapel.value = mapel
    mode.value = komponen
}

const closeForm = () => {
    selectedRombel.value = {}
    selectedMapel.value = {}
    mode.value = 'home'
}
</script>

<template>
    <div>
        <el-card>
            <template #header>
                <div class="card-toolbar flex items-center justify-between">
                    <div class="title">
                        <h3 class="text-lg font-bold">Penilaian {{page.props.rombels[0].label}} Semester {{page.props.periode.semester.label}} {{ page.props.periode.tapel.deskripsi }}</h3>
                    </div>
                    <div class="toolbar-items">
                        
                    </div>
                </div>
            </template>
            <div class="card-body">
                <el-collapse accordion>
                    <template v-for="(rombel,r) in page.props.rombels" :key="r">
                        <el-collapse-item>
                            <template #title>
                                <span>{{ rombel.label }} | {{ rombel.sekolah.nama }} | {{ rombel.siswas.length }} Siswa</span>
                            </template>
                            
                            <el-table :data="page.props.datas">
                                <el-table-column label="Mata Pelajaran" prop="label" />
                                <el-table-column label="Kategori" prop="kategori" />
                                <el-table-column label="Entri Nilai">
                                    <template #default="scope">
                                        <span class="flex items-center">
                                            <el-button type="primary" :disabled="guruKelas && scope.row.kode == 'pabp'" rounded size="small" @click="openForm(scope.row, rombel, 'harian')">Nilai Harian</el-button>
                                            <el-button type="primary" :disabled="guruKelas && scope.row.kode == 'pabp'" rounded size="small"  @click="openForm(scope.row, rombel, 'sts')">PTS</el-button>
                                            <el-button type="primary" :disabled="guruKelas && scope.row.kode == 'pabp'" rounded size="small"  @click="openForm(scope.row, rombel, 'sas')">PAS</el-button>
                                        </span>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-collapse-item>
                    </template>
                </el-collapse>
                <!-- {{ page.props.rombels }} -->
            </div>
        </el-card>
        <FormNilaiHarian v-if="mode == 'harian'" :rombel="selectedRombel" :mapel="selectedMapel" @close="closeForm" :open="mode == 'harian'" />
        <FormNilaiTS v-if="mode == 'sts'" :rombel="selectedRombel" :mapel="selectedMapel" @close="closeForm" :open="mode == 'sts'" />
        <FormNilaiAS v-if="mode == 'sas'" :rombel="selectedRombel" :mapel="selectedMapel" @close="closeForm" :open="mode == 'sas'" />
    </div>
</template>