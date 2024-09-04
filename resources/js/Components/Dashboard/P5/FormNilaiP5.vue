<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { ElNotification } from 'element-plus';

const page = usePage()
const props = defineProps({ proyek: Object})
const emit = defineEmits(['close'])
const show = ref(false)

const loading = ref({
    nilais: false,
})

const nilais = ref([])
const getNilaiP5 = async () => {
    loading.value.nilais = true
    axios.post(route('dashboard.p5.nilai.index', {
        _query: {
            rombel: props.proyek.rombel_id,
            proyek_id: props.proyek.id
        }
    }))
    .then(res => {
        nilais.value = res.data.nilais
        loading.value.nilais = false
    }).catch(err => console.log(err))
}

const simpanNilaiP5 = async () => {
    router.post(route('dashboard.p5.nilai.store'), 
        { 
            datas: nilais.value
        },
        {
            onStart: () => loading.value.nilais = true,
            onSuccess: () => ElNotification({ title: 'Info', message: page.props.flash.message, type: 'success'}),
            onError: errs => ElNotification({ title: 'Error', message: errs[0], type: 'error'}),
            onFinish: () => loading.value.nilais = false
        }
    )
}

onBeforeMount(() => {
    show.value = props.proyek !== null
    getNilaiP5()
})
</script>

<template>
<el-dialog fullscreen :show-close="false" v-model="show">
    <template #header>
        <div class="header flex items-center justify-between">
            <h3>Input Nilai P5 {{ props.proyek.nama }}</h3>
            <el-button circle type="danger" @click="emit('close')">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </template>
    <template #default>
        <el-card v-loading="loading.nilais">
            <el-table :data="nilais" max-height="80vh">
                <el-table-column label="No" type="index"></el-table-column>
                <el-table-column label="NISN" prop="siswa_id" width="150"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <template v-for="(apd, a) in props.proyek.apds" :key="a">
                    <el-table-column :label="apd.teks">
                        <template #default="scope">
                            <template v-for="(nilai, s) in scope.row.nilais">
                                <span v-if="nilai.apd_id == apd.id">
                                    <el-select v-model="nilai.skor">
                                        <el-option v-for="skor in ['BB', 'MB', 'BSH', 'SB']" :value="skor"></el-option>
                                    </el-select>
                                </span>
                            </template>
                        </template>
                    </el-table-column>
                </template>
            </el-table>
            <template #footer>
                <div class="flex items-center justify-end">
                    <el-button type="primary" @click="simpanNilaiP5">Simpan</el-button>
                </div>
            </template>
        </el-card>
    </template>
</el-dialog>
</template>