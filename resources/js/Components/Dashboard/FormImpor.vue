<script setup>
import { ref, computed } from 'vue'
import { read, utils } from 'xlsx'
import { router, usePage } from '@inertiajs/vue3'
import { ElNotification } from 'element-plus';

const page = usePage()
const props = defineProps({open: Boolean, fields: Array, title: String, url: String})
const emit = defineEmits(['close'])
const show = computed(() => props.open)
const datas = ref([])
const onFilePicked = async(e) => {
    const file = e.target.files[0]
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    const ws = wb.Sheets[wb.SheetNames[0]];
    datas.value = utils.sheet_to_json(ws)
}

const kirim = async() => {
    await router.post(route(props.url), {sekolah: page.props.auth.roles[0] === 'admin' ? null: page.props.sekolahs[0].id, datas: datas.value}, {
        onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Data Sekolah diimpor', type: 'success'})
            datas.value = []
            emit('close')
        },
        onError: err => {
            Object.keys(err).forEach(k => {
                setTimeout(() => {
                    ElNotification({ title: 'Error', message: err[k], type: 'error'})
                }, 500)
            })
        }
    })
}

const closeMe = () => {
    datas.value = []
    emit('close')
}
</script>

<template>
    <!-- <h1>Form Sekolah {{ props.open }}</h1> -->
    <el-dialog v-model="show" @close="closeMe" :fullscreen="true">
        <template #header>
            <div class="toolbar flex items-center justify-between">
                <h3 class="title">Impor Data {{ props.title }}</h3>
                <div class="toolbar-items flex items-center">
                    <input type="file" @change="onFilePicked" accept=".xlsx, xls, ods, csv" />
                    <el-button type="primary" @click="kirim" v-if="datas.length > 0">Simpan</el-button>
                </div>
            </div>
        </template>
        <div class="body">
            <el-table :data="datas" v-if="datas.length > 0" height="500px" size="small">
                <el-table-column v-for="(field, f) in props.fields" :key="f" :prop="field" :label="field.toUpperCase()" />
            </el-table>
            <el-alert v-else type="success">
                <span class="font-bold text-sky-600 text-xl">Pastikan File Excel dengan format kolom</span> <span class="text-sky-800 text-xl">{{ props.fields }}</span>
            </el-alert>
        </div>
    </el-dialog>
</template>