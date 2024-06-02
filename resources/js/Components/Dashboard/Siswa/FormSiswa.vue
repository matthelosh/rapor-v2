<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { ElNotification } from 'element-plus'
import axios from 'axios'

const page = usePage()
const props = defineProps({open: Boolean, selectedSiswa: Object})
const emit = defineEmits(['close'])
const show = computed(() => props.open)
const loading = ref(false)
const fotoUrl = ref('/img/tutwuri.png')
const fileFoto = ref(null)
const sekolahs = ref([])
const siswa = ref({
    nisn: '',
    nis: '',
    nik: '',
    nama: '',
    jk: '',
    agama: '',
    alamat: '',
    hp: '',
    email: '',
    foto: '',
    angkatan: '',
    sekolah_id: '',

})

const simpan = async() => {
    loading.value = true
    let fd = new FormData()
    if (fileFoto.value !== null) {
        fd.append('file', fileFoto.value)
    }
    Object.keys(siswa.value).forEach(k => {
        fd.append(k, siswa.value[k])
    })
    let url = siswa.value.id ? 'dashboard.siswa.update' : 'dashboard.siswa.store'
    if (siswa.value.id) {
        fd.append("_method", "PUT")
    }
    router.post(route(url), fd, {
        onSuccess: (page) => {
            // console.log(res)
            ElNotification({title: 'Info', message: 'Data Siswa disimpan', type: 'success'})
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

const onFotoPicked = (e) => {
    const file = e.target.files[0]
    let url  = URL.createObjectURL(file)
    fileFoto.value = file
    fotoUrl.value = url
    // console.log(e)
}

const closeMe = () => {
    loading.value = false
    emit('close')
}

const getSekolah = async() => {
    axios.post('/dashboard/sekolah/index').then( res => {
        sekolahs.value = res.data.sekolahs
    })
}

onBeforeMount(() => {
    if (props.selectedSiswa !== null) {
        siswa.value = props.selectedSiswa
    }

    getSekolah()
})
</script>

<template>
    <el-dialog v-model="show" width="800" title="Formulir Data Siswa" @close="closeMe" draggable>
        <el-row :gutter="10">
            <el-col :span="6" class="border-r bg-slate-100 p-2">
                <h4 class="text-center mb-2">Foto Siswa  <br /><small>[Klik untuk mengganti]</small></h4>
                <div>
                    <img class="mx-auto w-24 hover:cursor-pointer" :src="fotoUrl" alt="Foto" @click="$refs.fotoInput.click()">
                    <input type="file" placeholder="Pilih Foto Guru" ref="fotoInput" @change="onFotoPicked" class="hidden" accept=".jpg,.JPG,.png,.PNG,.bmp,.BMP,.svg, .SVG,.jpeg, .JPEG, .webp" />
                </div>
            </el-col>
            <el-col :span="18">
                <el-form label-position="top" size="small">
                    <el-row :gutter="6">
                        <el-col :span="8">
                            <el-form-item label="NISN">
                                <el-input v-model="siswa.nisn" placeholder="Masukkan NISN" required />
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="NIS">
                                <el-input v-model="siswa.nis" placeholder="NIS" required />
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="NIK">
                                <el-input v-model="siswa.nik" placeholder="NIK Siswa" required />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="6">
                        <el-col :span="12">
                            <el-form-item label="Nama Siswa">
                                <el-input v-model="siswa.nama" placeholder="Nama Siswa" required />
                            </el-form-item>
                        </el-col>
                    
                        <el-col :span="6">
                            <el-form-item label="Jenis Kelamin">
                                <el-select v-model="siswa.jk" placeholder="Jenis Kelamin">
                                    <el-option value="Laki-laki" />
                                    <el-option value="Perempuan" />
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Agama">
                                <el-select v-model="siswa.agama" placeholder="Agama">
                                    <el-option value="Islam" />
                                    <el-option value="Kristen" />
                                    <el-option value="Katolik" />
                                    <el-option value="Hindu" />
                                    <el-option value="Budha" />
                                    <el-option value="Konghuchu" />
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="6">
                        <el-col :span="14">
                            <el-form-item label="Alamat">
                                <el-input type="textarea" v-model="siswa.alamat" rows="1" :autosize="{ minRows: 1, maxRows: 2 }" placeholder="Alamat" />
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="HP">
                                <el-input  v-model="siswa.hp" placeholder="HP" />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="6">
                        <el-col :span="14">
                            <el-form-item label="Email">
                                <el-input  v-model="siswa.email" placeholder="Email" />
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="Lembaga">
                                <el-select v-model="siswa.sekolah_id" placeholder="Lembaga" collapse-tags filterable>
                                    <el-option v-for="(sekolah, s) in page.props.sekolahs" :key="s" :value="sekolah.npsn" :label="sekolah.nama" />
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row justify="center">
                        <el-button type="primary" :loading="loading" @click="simpan">Simpan</el-button>
                    </el-row>
                </el-form>
            </el-col>
        </el-row>
    </el-dialog>
</template>