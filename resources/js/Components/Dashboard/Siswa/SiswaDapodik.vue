<script setup>
import { Icon } from '@iconify/vue';
import { ref, onBeforeMount, reactive } from 'vue';

const props = defineProps({show: Boolean})
const emit = defineEmits(['close'])
const buka = ref(false)
const loading = ref(false)
const siswas = ref([])

const data = reactive({
    npsn: '20518848', ipDapodik:'192.168.1.16'
})

const getSiswa = async() => {
    loading.value = true
    axios.post(route('dashboard.siswa.impor.dapodik'), data)
        .then(res => {
            siswas.value = res.data.siswas
            loading.value = false
        }).catch(err => {
            console.log(res)
            loading.value = false
        })
}
onBeforeMount(() => {
    buka.value = props.show
})
</script>

<template>
<el-dialog fullscreen :show-close=false v-model="buka">
    <template #header>
        <div class="flex items-center justify-between">
            <h3>Impor Siswa Dari Dapodik</h3>
            <el-button circle type="danger" @click="emit('close')">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </template>
    <template #default>
        <div class="flex items-center justify-center">
            <div v-if="siswas.length < 1">
                <el-card class="w-[400px]" >
                    <el-form label-position="top">
                        <el-form-item label="NPSN Sekolah">
                            <el-input v-model="data.npsn" placeholder="Masukkan NPSN Sekolah"></el-input>
                        </el-form-item>
                        <el-form-item label="Alamat IP Laptop Dapodik">
                            <el-input v-model="data.ipDapodik" placeholder="Masukkan Alamat IP Laptop Dapodik"></el-input>
                        </el-form-item>
                        <div class="flex justify-center">
                            <el-button type="primary" @click="getSiswa">Ambil Data</el-button>
                        </div>
                    </el-form>
                </el-card>
                <span v-if="loading">
                    <Icon icon="mdi-loading" class="text-6xl mx-auto text-sky-500 my-4 animate-spin" />
                    <h3 class="text-center text-sky-500">Tunggu ya..</h3>
                </span>
            </div>
            <el-table v-else :data="siswas" max-height="80vh">
                <el-table-column label="ID Dapodik" prop="peserta_didik_id"></el-table-column>
                <el-table-column label="NISN" prop="nisn"></el-table-column>
                <el-table-column label="NIS" prop="nis"></el-table-column>
                <el-table-column label="NIK" prop="nik"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <el-table-column label="Jenis Kelamin" prop="jenis_kelamin"></el-table-column>
                <el-table-column label="Tempat Lahir" prop="tempat_lahir"></el-table-column>
                <el-table-column label="Tanggal Lahir" prop="tanggal_lahir"></el-table-column>
            </el-table>
        </div>
    </template>
</el-dialog>
</template>