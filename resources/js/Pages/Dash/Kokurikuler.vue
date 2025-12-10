<script setup>
import { Head, router, usePage } from "@inertiajs/vue3";
import DashLayout from "@/Layouts/DashLayout.vue";
import { onBeforeMount, onMounted, ref } from "vue";

const page = usePage();

const formNilai = ref(false)
const siswas = ref([])
const nilaiIndex = ref(0)
const inputNilai = (index, siswa) => {
    formNilai.value = true
    nilaiIndex.value = index
    selectedNilai.value = {
        index:index,
        siswa_id: siswa.nisn,
        rombel_id: page.props.rombel.kode,
        guru_id: page.props.auth.userable?.nip,
        tapel: page.props.periode.tapel.kode,
        semester: page.props.periode.semester.kode,
        deskripsi: siswa.kokurikulers[0]?.deskripsi
    }
}

const nilais = ref([])
const selectedNilai = ref({
    id: '',
    index: '',
    siswa_id: '',
    rombel_id: '',
    guru_id: '',
    deskripsi: '',
    tapel: '',
    semester: ''
})

const loading = ref(false)
const simpanKokurikuler = async () => {
    router.post(route('dashboard.kokurikuler.store'), selectedNilai.value, {
        onStart: () => loading.value = true,
        onSuccess: () => {
            router.reload({only: ['rombel']})
            ElNotification({type: 'info', message: page.props.flash.message, title: 'Info'})
            formNilai.value = false
        },
        onError: () => {
            Object.entries(page.props.errors).forEach(err => {
                ElNotification({type: 'error', message: err, title: 'Error'})
            })
        },
        onFinish: () => {
            loading.value = false
        }
    })
}

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
                        <el-table-column label="Kokurikuler">
                            <template #default="{row}">
                                {{ row.kokurikulers[0]?.deskripsi }}
                            </template>
                        </el-table-column>
                        <el-table-column label="AKSI">
                            <template #default="scope">
                                <el-button type="primary" :native-type="null" @click="inputNilai(scope.$index, scope.row)">
                                    Input Kokurikuler
                                </el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </template>
        </el-card>
    
        <el-dialog v-model="formNilai">
            <template #header>
                Pencapaian Kokurikuler {{ page.props.rombel.siswas[selectedNilai.index]?.nama }} 
            </template>
            <div>
                <el-input type="textarea" v-model="selectedNilai.deskripsi" />
            </div>
            <template #footer>
                <el-button :native-type="null" type="primary" @click="simpanKokurikuler">Simpan</el-button>
            </template>
        </el-dialog>
    </div>
</DashLayout>
</template>