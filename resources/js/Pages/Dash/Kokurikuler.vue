<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import DashLayout from "@/Layouts/DashLayout.vue";
import { onBeforeMount, onMounted, ref } from "vue";

const page = usePage();

const formNilai = ref(false)
const siswas = ref([])
const nilaiIndex = ref(0)
const inputNilai = (siswa) => {
    formNilai.value = true
    nilaiIndex.value = nilais.value.lastIndexOf(nilai => nilai.siswa_id === siswa.nisn)
}

const nilais = ref([])

onBeforeMount(() => {
    [...page.props.rombel.siswas].forEach(siswa => {
        nilais.value.push({siswa_id: siswa.nisn, nilai: ''})
    })
})
</script>

<template>
<Head title="Nilai Kokurikuler" />
<DashLayout>
    <template #header>
        <h3>Nilai Kokurikuler {{ page.props.rombel.label }}</h3>
    </template>
    <div>
        <el-card>
            <template #header>
                <div class="flex items-center justify-between">
                    <h3>Input Penilaian Kokurikuler</h3>
                </div>
                <div>
                    <el-table :data="page.props.rombel.siswas">
                        <el-table-column label="NISN">
                            <template #default="{row}">
                                {{ row.nisn }} / {{ row.nis ?? '-' }}
                            </template>
                        </el-table-column>
                        <el-table-column label="NAMA">
                            <template #default="{row}">
                                {{ row.nama }}
                            </template>
                        </el-table-column>
                        <el-table-column label="AKSI">
                            <template #default="{row}">
                                <el-button type="primary" :native-type="null" @click="inputNilai(row)">
                                    Input
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </template>
        </el-card>
    
        <el-dialog v-model="formNilai">
            <div>
                <el-input type="textarea" v-model="nilais[nilaiIndex].nilai" />
            </div>
        </el-dialog>
    </div>
</DashLayout>
</template>