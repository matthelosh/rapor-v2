<script setup>
import { ref, onBeforeMount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

const page = usePage()
const props = defineProps({open: Boolean})
const emit = defineEmits(['close'])
const show = ref(false)
const showForm = ref(false)
const newEkskul = ref({})

const editEkskul = (item) => {
    newEkskul.value = item
    showForm.value = true
}

const simpan = () => {
    const elLoading = ElLoading.service({
        lock: true,
        text: 'Loading',
        background: 'rgba(0, 0, 0, 0.7)',
    })
    router.post(route('dashboard.pembelajaran.ekskul.store'), newEkskul.value, {
        onSuccess: () => {
            ElNotification({
                title: 'Info',
                message: page.props.flash.message,
                type: 'success',
            })
            router.reload({only: ['ekskuls']})
            newEkskul.value = {}
            showForm.value = false
        },
        onFinish: () => {
            elLoading.close();
        }
    });
}

const hapusEkskul = async (id) => {
    router.delete(route('dashboard.pembelajaran.ekskul.destroy', {id: id}), {
         onSuccess: () => {
            ElNotification({
                title: 'Info',
                message: page.props.flash.message,
                type: 'success',
            })
            router.reload({only: ['ekskuls']})
            newEkskul.value = {}
            showForm.value = false
        },
        onFinish: () => {
            elLoading.close();
        }
    });
}
onBeforeMount(() => {
    show.value = props.open
})
</script>

<template>
    <el-dialog v-model="show" @close="emit('close')">
        <template #header>
            <span class="flex items-center justify-between">
                <h3 class="text-sky-800 text-lg">Tambah <span class="font-black">Ekstrakurikuler</span>
            </h3>
            </span>
        </template>
        <template #default>
            <div class="flex justify-start pb-2">
                <el-button :native-type="null" type="primary" @click="showForm = true" v-if="!showForm" size="small">Tambah</el-button>
            </div>
            <el-card class="dialog-body" v-if="!showForm">
                <el-table :data="page.props.ekskuls" max-height="400" class="shadow">
                    <el-table-column label="Kode" prop="kode" width="150"></el-table-column>
                    <el-table-column label="Nama" prop="nama" width="200"></el-table-column>
                    <el-table-column label="Keterangan" prop="keterangan"></el-table-column>
                    <el-table-column label="Opsi" width="100">
                        <template #default="{row}">
                            <span>
                                <el-button circle :native-type="null" type="warning" size="small" @click="editEkskul(row)">
                                    <Icon icon="mdi-pencil"></Icon>
                                </el-button>
                                <el-button circle :native-type="null" type="danger" size="small" @click="hapusEkskul(row.id)">
                                    <Icon icon="mdi-close"></Icon>
                                </el-button>
                            </span>
                        </template>
                    </el-table-column>
                </el-table>
            </el-card>
            <el-card class="dialog-body" v-else>
                <el-form v-model="newEkskul" label-position="top">
                    <el-row :gutter="10">
                        <el-col :span="4"> 
                            <el-form-item label="Kode">
                                <el-input v-model="newEkskul.kode" placeholder="Kode"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="12">
                            <el-form-item label="Nama">
                                <el-input v-model="newEkskul.nama" placeholder="Nama"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col>
                            <el-form-item label="Keterangan">
                                <el-input v-model="newEkskul.keterangan" placeholder="Keterangan"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-form>
                <template #footer>
                    <div class="flex justify-end">
                    <el-button type="danger" text @click="showForm = false">Batal</el-button>
                    <el-button type="primary" @click="simpan">Simpan</el-button>
                    </div>
                </template>
            </el-card>
                
        </template>
    </el-dialog>
</template>