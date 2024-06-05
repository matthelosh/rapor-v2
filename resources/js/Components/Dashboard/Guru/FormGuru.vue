<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { ElNotification } from 'element-plus'

const page = usePage()
const props = defineProps({open: Boolean, selectedGuru: Object})
const emit = defineEmits(['close'])
const show = computed(() => props.open)
const loading = ref(false)
const fotoUrl = ref('/img/tutwuri.png')
const fileFoto = ref(null)
const guru = ref({
    nip: '198407032019031007',
    gelar_depan: 'Dr.',
    nama:'Bejo',
    gelar_belakang: 'S. Pd.',
    jk: 'Laki-laki',
    alamat: 'Malang',
    hp: '-',
    status: 'PNS',
    email: 'bejo@rmail.com',
    foto: null,
    agama: 'Islam',
    pangkat: 'III/B',
    jabatan: 'guru_kelas',
    // sekolahs: [22]
})

const simpan = async() => {
    loading.value = true

    let fd = new FormData()
    if (fileFoto.value !== null) {
        fd.append('file', fileFoto.value)
    }
    Object.keys(guru.value).forEach(k => {
        fd.append(k, guru.value[k])
    })
    let url = guru.value.id ? 'dashboard.guru.update' : 'dashboard.guru.store'
    // let httpMethod = sekolah.value.id ? 'put' : 'post'
    if (guru.value.id) {
        fd.append("_method", "PUT")
    }
    router.post(route(url), fd, {
        onSuccess: (page) => {
            // console.log(res)
            ElNotification({title: 'Info', message: 'Data Guru disimpan', type: 'success'})
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

onBeforeMount(() => {
    if (props.selectedGuru !== null) {
        guru.value = props.selectedGuru
        guru.value.sekolahs = props.selectedGuru.sekolahs.map(s => s.id)
    } else {
        if (page.props.auth.roles.includes('ops')) {
            guru.value.sekolahs = page.props.sekolahs.map(s => s.id)
        }
    }
})
</script>

<template>
    <!-- <h1>Form Sekolah {{ props.open }}</h1> -->
    <el-dialog v-model="show" width="800" title="Formulir Data Guru" @close="closeMe" draggable>
        
        <el-row :gutter="10">
            <el-col :span="6" class="border-r bg-slate-100 p-2">
                <h4 class="text-center mb-2">Foto Guru  <br /><small>[Klik untuk mengganti]</small></h4>
                <div>
                    <img class="mx-auto w-24 hover:cursor-pointer" :src="fotoUrl" alt="Foto" @click="$refs.fotoInput.click()">
                    <input type="file" placeholder="Pilih Foto Guru" ref="fotoInput" @change="onFotoPicked" class="hidden" accept=".jpg,.JPG,.png,.PNG,.bmp,.BMP,.svg, .SVG,.jpeg, .JPEG, .webp" />
                </div>
            </el-col>
            <el-col :span="18">
                <el-form label-position="top" size="small">
                    <el-row :gutter="6">
                        <el-col :span="6">
                            <el-form-item label="NIP">
                                <el-input v-model="guru.nip" placeholder="Masukkan NIP" required />
                            </el-form-item>
                        </el-col>
                        <el-col :span="3">
                            <el-form-item label="Gelar Dpn">
                                <el-input v-model="guru.gelar_depan" placeholder="Gelar Dpn" required />
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="Nama Guru">
                                <el-input v-model="guru.nama" placeholder="Nama Guru" required />
                            </el-form-item>
                        </el-col>
                        <el-col :span="5">
                            <el-form-item label="Gelar Blk">
                                <el-input v-model="guru.gelar_belakang" placeholder="Gelar Belakang" required />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="6">
                        <el-col :span="6">
                            <el-form-item label="Jenis Kelamin">
                                <el-select v-model="guru.jk" placeholder="Jenis Kelamin">
                                    <el-option value="Laki-laki" />
                                    <el-option value="Perempuan" />
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Agama">
                                <el-select v-model="guru.agama" placeholder="Agama">
                                    <el-option value="Islam" />
                                    <el-option value="Kristen" />
                                    <el-option value="Katolik" />
                                    <el-option value="Hindu" />
                                    <el-option value="Budha" />
                                    <el-option value="Konghuchu" />
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="12">
                            <el-form-item label="Alamat">
                                <el-input type="textarea" v-model="guru.alamat" placeholder="Alamat" />
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="6">
                        <el-col :span="8">
                            <el-form-item label="HP">
                                <el-input  v-model="guru.hp" placeholder="HP" />
                            </el-form-item>
                        </el-col>
                        <el-col :span="4">
                            <el-form-item label="Status">
                                <el-select  v-model="guru.status" placeholder="Status">
                                    <el-option value="pns" label="PNS" />
                                    <el-option value="p3k" label="P3K" />
                                    <el-option value="gtt" label="GTT" />
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Pangkat">
                                <el-select  v-model="guru.pangkat" placeholder="Pangkat">
                                    <el-option value="-" label="-" />
                                    <el-option value="IIIa" label="III/A" />
                                    <el-option value="IIIb" label="III/B" />
                                    <el-option value="IIIc" label="III/C" />
                                    <el-option value="IIId" label="III/D" />
                                    <el-option value="IVa" label="IV/A" />
                                    <el-option value="IVb" label="IV/B" />
                                    <el-option value="IVc" label="IV/C" />
                                    <el-option value="IVd" label="IV/D" />
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Jabatan">
                                <el-select  v-model="guru.jabatan" placeholder="Jabatan">
                                    <el-option value="ks" label="Kepala Sekolah" />
                                    <el-option value="guru_kelas" label="Guru Kelas" />
                                    <el-option value="guru_agama" label="Guru Agama" />
                                    <el-option value="guru_pjok" label="Guru PJOK" />
                                    <el-option value="guru_inggris" label="Guru B. Inggris" />
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="14">
                            <el-form-item label="Email">
                                <el-input  v-model="guru.email" placeholder="Email" />
                            </el-form-item>
                        </el-col>
                        <el-col :span="10">
                            <el-form-item label="Lembaga">
                                <el-select v-model="guru.sekolahs" placeholder="Lembaga" multiple collapse-tags>
                                    <el-option v-for="(sekolah, s) in page.props.sekolahs" :key="s" :value="sekolah.id" :label="sekolah.nama" />
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