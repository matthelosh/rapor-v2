<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { ElNotification } from 'element-plus'
import axios from 'axios'

const page = usePage()
const props = defineProps({open: Boolean, selectedSekolah: Object})
const emit = defineEmits(['close'])
const show = computed(() => props.open)
const loading = ref(false)
const logoUrl = ref('/img/tutwuri.png')
const fileLogo = ref(null)
const gurus = ref([])
const sekolah = ref({
    npsn: null,
    nama: 'SD Negeri',
    alamat: 'Jl. Kaki',
    desa: 'Sukamakmur',
    kecamatan: 'Wagir',
    kabupaten: 'Malang',
    kode_pos: '65158',
    telp: null,
    email: null,
    website: null,
    ks_id: ''

})

const simpan = async() => {
    loading.value = true
    let fd = new FormData()
    if (fileLogo.value !== null) {
        fd.append('file', fileLogo.value)
    }
    Object.keys(sekolah.value).forEach(k => {
        fd.append(k, sekolah.value[k])
    })
    let url = sekolah.value.id ? 'dashboard.sekolah.update' : 'dashboard.sekolah.store'
    // let httpMethod = sekolah.value.id ? 'put' : 'post'
    if (sekolah.value.id) {
        fd.append("_method", "PUT")
    }
    router.post(route(url), fd, {
        onSuccess: (page) => {
            // console.log(res)
            ElNotification({title: 'Info', message: 'Data Sekolah disimpan', type: 'success'})
            loading.value = false
            emit('close')
        },
        onError: (err) => {
            console.log(err)
            Object.keys(err).forEach(k => {
                ElNotification({title: 'Error', message: err[k], type: 'error'})
            })
            loading.value = false
        }
    })
}

const onLogoPicked = (e) => {
    const file = e.target.files[0]
    let url  = URL.createObjectURL(file)
    fileLogo.value = file
    logoUrl.value = url
    // console.log(e)
}

const closeMe = () => {
    loading.value = false
    emit('close')
}

const getGurus = async() => {
    axios.post(route('dashboard.guru.show', {_query: {sekolah: page.props.auth.roles.includes('admin') ? 'all' : sekolah.value.id}})).then( res => {
        gurus.value = res.data.gurus
    })
}

onBeforeMount(() => {
    if (props.selectedSekolah !== null) {
        sekolah.value = props.selectedSekolah
    }

    getGurus()
})
</script>

<template>
    <!-- <h1>Form Sekolah {{ props.open }}</h1> -->
    <el-dialog v-model="show" width="800" title="Formulir Data Sekolah" @close="closeMe" draggable>
        <!-- {{ gurus }} -->
        <el-form label-position="top" size="small">
            <el-row :gutter="6">
                <el-col :span="4">
                    <el-form-item label="NPSN">
                        <el-input v-model="sekolah.npsn" placeholder="Masukkan NPSN" required />
                    </el-form-item>
                </el-col>
                <el-col :span="20">
                    <el-form-item label="Nama Sekolah">
                        <el-input v-model="sekolah.nama" placeholder="Nama Sekolah" required />
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="6">
                <el-col :span="12">
                    <el-form-item label="Alamat">
                        <el-input type="textarea" v-model="sekolah.alamat" placeholder="Alamat" />
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item label="Logo">
                        <input type="file" placeholder="Pilih gambar logo" @change="onLogoPicked" />
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <img class="mx-auto w-14" :src="logoUrl" alt="Logo">
                </el-col>
                
            </el-row>
            <el-row :gutter="6">
                <el-col :span="12">
                    <el-form-item label="Desa">
                        <el-input  v-model="sekolah.desa" placeholder="Desa" />
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item label="Kecamatan">
                        <el-input v-model="sekolah.kecamatan" placeholder="Kecamatan" />
                    </el-form-item>
                </el-col>
                <el-col :span="6">
                    <el-form-item label="Kabupaten">
                        <el-input  v-model="sekolah.kabupaten" placeholder="Kabupaten" />
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="6">
                <el-col :span="4">
                    <el-form-item label="Kode Pos">
                        <el-input v-model="sekolah.kode_pos" placeholder="Kode Pos" />
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="No. Telepon">
                        <el-input  v-model="sekolah.telp" placeholder="No. Telepon" />
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Email">
                        <el-input  v-model="sekolah.email" placeholder="Email" />
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="6">
                <el-col :span="24">
                    <el-form-item label="Website">
                        <el-input v-model="sekolah.website" placeholder="Website" />
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="6">
                <el-col :span="12">
                    <el-form-item label="Kepala Sekolah">
                        <el-select v-model="sekolah.ks_id" placeholder="Kepala Sekolah" filterable>
                            <el-option v-for="(guru, g) in gurus" :key="g" :value="guru.id" :label="`${guru.nip} | ${guru.nama}`" />
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Gugus">
                        <el-select v-model="sekolah.gugus_id" placeholder="Gugus" filterable>
                            <el-option v-for="(gugus, gu) in page.props.guguses" :key="gu" :value="gugus.id" :label="`${gugus.nama}`" />
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row justify="center">
                <el-button type="primary" :loading="loading" @click="simpan">Simpan</el-button>
            </el-row>
        </el-form>
    </el-dialog>
</template>