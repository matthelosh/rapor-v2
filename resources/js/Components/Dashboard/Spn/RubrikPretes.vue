<script setup>
import { Icon } from '@iconify/vue';
import axios from 'axios';
import { ElNotification } from 'element-plus';
import { onBeforeMount, ref } from 'vue';

const props = defineProps({rombel: Object})
const emit = defineEmits(['close'])
const show = ref(false)
const siswas = ref([])

const getData = async() => {
    let datas = []
    axios.post(route('dashboard.spn.pretes.siswa', {_query: {rombelId: props.rombel.kode}}))
        .then(res => {
            res.data.siswas.forEach(siswa => {

                datas.push({
                    nisn: siswa.nisn,
                    nama: siswa.nama,
                    jilid: siswa.jilids[0]?.id ?? 1
                })
            siswas.value = datas
        })
    })
}

const simpan = async() => {
    axios.post(route('dashboard.spn.pretes.store', {_query: {rombelId: props.rombel.kode}}), {siswas: siswas.value})
        .then(res => [
            ElNotification({title: 'Info', message: res.data.message, type: 'success'})
        ])
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
        <el-table :data="siswas" v-loading="props.rombel == null" max-height="80vh">
            <el-table-column label="NISN" prop="nisn"></el-table-column>
            <el-table-column label="Nama" prop="nama"></el-table-column>
            <el-table-column label="Jilid">
                <template #default="{row}">
                    <el-select v-model="row.jilid">
                        <el-option v-for="jil in [1,2,3]" :value="jil" :label="`Jilid ${jil}`"></el-option>
                    </el-select>
                    <!-- {{ row.jilid }} -->
                </template>
            </el-table-column>
        </el-table>
    </div>
    <template #footer>
        <div class="flex justify-end">
            <el-button type="primary" @click="simpan">Simpan</el-button>
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