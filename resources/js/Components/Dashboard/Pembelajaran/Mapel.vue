<script setup>
import { ref, onBeforeMount } from 'vue'
import { router, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
const props = defineProps({open: Boolean})
const emit = defineEmits(['close'])

const page = usePage()
const show = ref(false)
const showForm = ref(false)

const mapel = ref({})
const edit = (item) => {
    mapel.value = item
    showForm.value = true
}
const hapus = async (id) => {
    const elLoading = ElLoading.service({
        lock: true,
        text: 'Loading',
        background: 'rgba(0, 0, 0, 0.7)'
    })

    router.delete(route('dashboard.mapel.destroy', {id: id}), {
        // onStart: () => {  
        onSuccess: (page) => {
            // emit('close')
            ElNotification({
                title: 'Info',
                message: page.props.flash.message,
                type: 'success'
            })
            showForm.value = false
            router.reload({only: ['mapels']})
        },
        onError: (errs) => {
            console.log(errs)
        },
        onFinish: () => {
            elLoading.close()
        }
    })
}

const simpanMapel = async () => {
    // console.log(mapel.value)
    const elLoading = ElLoading.service({
        lock: true,
        text: 'Loading',
        background: 'rgba(0, 0, 0, 0.7)'
    })

    router.post(route('dashboard.mapel.store'), mapel.value, {
        // onStart: () => {
        //     elLoading.start()
        // },
        onSuccess: (page) => {
            // emit('close')
            ElNotification({
                title: 'Info',
                message: page.props.flash.message,
                type: 'success'
            })
            showForm.value = false
            router.reload({only: ['mapels']})
        },
        onError: (errs) => {
            console.log(errs)
        },
        onFinish: () => {
            elLoading.close()
        }
    })
}

onBeforeMount(() => {
    show.value = props.open
})
</script>

<template>
    <el-dialog v-model="show" @close="emit('close')">
        <template #header>
            <span class="flex items-center justify-between">Data Mapel</span>
        </template>
        <template #default>
            <div class="flex justify-end">

                <el-button :native-type="null" type="primary" size="small" @click="showForm = true" v-if="!showForm">Tambah</el-button>
            </div>
            <el-table :data="page.props.mapels" v-if="!showForm" max-height="400">
                <el-table-column label="Kode" prop="kode"></el-table-column>
                <el-table-column label="Nama" prop="label"></el-table-column>
                <el-table-column label="Fase" prop="fase"></el-table-column>
                <el-table-column label="Opsi" width="100">
                    <template #default="{row}">
                        <el-button circle :native-type="null" type="warning" size="small" @click="edit(row)">
                            <Icon icon="mdi-pencil" />
                        </el-button>
                        <el-button circle :native-type="null" type="danger" size="small" @click="hapus(row.id)">
                            <Icon icon="mdi-close" />
                        </el-button>    
                    </template>
                </el-table-column>
            </el-table>
            <el-card v-else>
                <el-form v-model="mapel" label-position="top">
                    <el-row :gutter="10">
                        <el-col :span="4">
                            <el-form-item label="Kode">
                                <el-input v-model="mapel.kode" placeholder="Kode Mapel" />
                            </el-form-item>
                            
                        
                        </el-col>
                        <el-col :span="12">
                            <el-form-item label="Nama">
                                <el-input v-model="mapel.label" placeholder="Nama Mapel" />
                            </el-form-item>        
                    
                        </el-col>
                        <el-col :span="3">
                            <el-form-item label="Fase">
                                <el-input v-model="mapel.fase" placeholder="Pisahkan dengan koma jika lebih dari satu" />
                            </el-form-item>        
                    
                        </el-col>
                        <el-col :span="5">
                            <el-form-item label="Kategori">
                                <el-select v-model="mapel.kategori" placeholder="Kategori Mapel">
                                    <el-option v-for="k in ['Wajib','Mulok']" :key="k" :label="k" :value="k"></el-option>"
                                </el-select>

                            </el-form-item>        
                    
                        </el-col>
                    </el-row>
                    <el-row :gutter="10">
                        <el-col :span="24">
                            <el-input v-model="mapel.deskripsi" placeholder="Keterangan" type="textarea"></el-input>
                        </el-col>
                    </el-row>
                </el-form>
                <template #footer>
                    <div class="flex justify-end">
                        <el-button :native-type="null" type="danger" size="small" @click="showForm = false">Batal</el-button>
                        <el-button :native-type="null" type="primary" size="small"  @click="simpanMapel">Simpan</el-button>
                    </div>
                </template>
            </el-card>
        </template>
    </el-dialog>
</template>