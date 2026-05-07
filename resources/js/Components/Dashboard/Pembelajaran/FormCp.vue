<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
import { onBeforeMount, reactive, ref } from 'vue';

const page = usePage()
const props = defineProps({show: Boolean, mapel: Object})
const open = ref(false)
const emit = defineEmits(['close'])
const loading = ref(false)
const formCp = useForm({
    fase:'',
    mapel_id: '',
    teks: ''
})

const simpan = () => {
    formCp.post(route('dashboard.cp.store'), {
        onStart: () => loading.value = true,
        onSuccess: () => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
            emit('close')
        }
    })
}

onBeforeMount(() => {
    open.value = props.show
    formCp.mapel_id = props.mapel.kode
})
</script>

<template>
    <el-dialog v-model="open" :show-close="false" @close="emit('close')">
        <template #header>
            <div class="flex justify-between items-center">
                <h1 class="text-lg font-bold text-sky-700">Form Capaian Pembelajaran {{ props.mapel?.label }}</h1>
            </div>
        </template>

        <el-form v-model="formCp" label-position="top" @submit.prevent="simpan">
            <el-form-item label="Fase">
                <el-select v-model="formCp.fase" placeholder="Pilih Fase">
                    <el-option v-for="fase in ['A', 'B','C']" :value="fase"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Teks CP">
                <el-input type="textarea" autosize v-model="formCp.teks" placeholder="Teks Capaian PEmbelajaran"></el-input>
            </el-form-item>
            <el-row justify="center">
                <el-button type="primary" @click="simpan">Simpan</el-button>
            </el-row>
        </el-form>
    </el-dialog>
</template>