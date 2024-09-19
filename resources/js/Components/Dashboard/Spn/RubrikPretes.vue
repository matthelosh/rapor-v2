<script setup>
import { Icon } from '@iconify/vue';
import axios from 'axios';
import { ElNotification } from 'element-plus';
import { onBeforeMount, ref } from 'vue';

const props = defineProps({rombel: Object, jilids: Array})
const emit = defineEmits(['close'])
const show = ref(false)
const siswas = ref([])
const loading = ref(false)

const getData = async() => {
    let datas = []
    loading.value = true
    axios.post(route('dashboard.spn.pretes.siswa', {_query: {rombelId: props.rombel.kode}}))
        .then(res => {
            res.data.siswas.forEach(siswa => {

                datas.push({
                    nisn: siswa.nisn,
                    nama: siswa.nama,
                    jilid: siswa.jilids[0]?.id ?? props.jilids.find(jilid => jilid.juz == 1).id
                })
            siswas.value = datas
            loading.value = false
        })
    })
}

const simpan = async(siswa) => {
    loading.value = true
    axios.post(route('dashboard.spn.pretes.siswa.attach', {siswaId: siswa.nisn}), {data: siswa})
    .then(res => {
            ElNotification({title: 'Info', message: res.data.message, type: 'success'})
            loading.value = false
    })
}

const simpanSemua = async() => {
    loading.value = true
    axios.post(route('dashboard.spn.pretes.siswa.attach-all', {_query: {rombelId: props.rombel.kode}}), {siswas: siswas.value})
        .then(res => {
            ElNotification({title: 'Info', message: res.data.message, type: 'success'})
            loading.value = false
    })
}
onBeforeMount(() => {
    show.value = props.rombel !== null
    getData()
})
</script>

<template>
<el-dialog fullscreen :show-close="false" v-model="show">
    <template #header>
        <div class="toolbar flex items-center justify-between">
            <h3>Rubrik Pretes SPN Kelas {{ props.rombel.label }}</h3>

            <el-button circle type="danger" @click="emit('close')">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </template>

    <div class="content">
        <el-table :data="siswas" v-loading="loading" height="80vh" max-height="80vh">
            <el-table-column label="NISN" prop="nisn"></el-table-column>
            <el-table-column label="Nama" prop="nama"></el-table-column>
            <el-table-column label="Jilid">
                <template #default="{row}">
                    <el-select v-model="row.jilid" placeholder="Pilih jilid" @change="simpan(row)">
                        <el-option v-for="jil in props.jilids" :value="jil.id" :label="`Jilid ${jil.juz}`"></el-option>
                    </el-select>
                    <!-- {{ row.jilid }} -->
                </template>
            </el-table-column>
        </el-table>
    </div>
    <template #footer>
        <div class="flex justify-end">
            <el-button type="primary" @click="simpanSemua">Simpan</el-button>
        </div>
    </template>
</el-dialog>
</template>

<style scope>
.el-dialog__body {
    /* background: lime!important; */
    padding-top: 10px;
}

</style>