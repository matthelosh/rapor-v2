<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import axios from 'axios';
import { ElNotification } from 'element-plus';

const page = usePage()
const props = defineProps({ siswa: Object, open: Boolean })
// const emit = defineEmits(['close'])

const ortu = ref({
    siswa_id: props.siswa.nisn,
    ayah: {
        nama: '',
        relasi:'Ayah',
        alamat:'',
        hp:'',
        pekerjaan: ''
    },
    ibu: {
        nama: '',
        relasi:'Ibu',
        alamat:'',
        hp:'',
        pekerjaan: ''
    },
    wali: {
        nama: '',
        relasi:'Wali',
        alamat:'',
        hp:'',
        pekerjaan: ''
    }
})

const fields = ref([
    
]);

const pekerjaans = ref([])

const getPekerjaan = async() => {
    axios.get(route('dashboard.siswa.ortu.pekerjaan.index'))
            .then(res => {
                pekerjaans.value = res.data.pekerjaans
            })
}

const simpan = async() => {
    console.log(ortu.value)
    axios.post(route('dashboard.siswa.ortu.store'), {ortu: ortu.value})
            .then(res => {
                ElNotification({title: 'Info', message: res.data.message, type: 'success'})
            }).catch(err => {
                ElNotification({title: 'Error', message: err.response.data.message, type: 'error'})
            })
}

onBeforeMount(async () => {
    // ortu.value = props.siswa.or
    getPekerjaan()
})
</script>

<template>
<el-dialog v-model="props.open" :show-close="false">
    <template #header="{close}">
        <div class="header flex items-center justify-between">

            <h3 class="uppercase">Formulir Orang Tua / Wali dari {{ siswa.nama }}</h3>
            <el-button type="danger" @click="close" circle>
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </template>
    <div class="dialog-body">
        <el-form v-model="ortu" label-position="top">
            <!-- Data Ayah -->
            <el-row :gutter="10">
                <el-col>
                    <el-card sizr="small">
                        <template #header>
                            <h3 class="font-bold text-slate-700 flex items-center uppercase"><Icon icon="mdi:human-male" class="text-lg" /> Data Ayah</h3>
                        </template>
                        <div class="card-bod">
                            <el-row :gutter="10">
                                <el-col :span="12">
                                    <el-form-item label="NIK Ayah">
                                        <el-input placeholder="N.I.K. Ayah" v-model="ortu.ayah.nik"></el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="12">
                                    <el-form-item label="Nama Ayah">
                                        <el-input placeholder="Nama Ayah" v-model="ortu.ayah.nama"></el-input>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-row :gutter="10">
                                <el-col :span="8">
                                    <el-form-item label="Hubungan">
                                        <el-select placeholder="Pilih Hubungan" v-model="ortu.ayah.relasi">
                                            <el-option v-for="relasi in ['Ayah', 'Ibu', 'Wali']" :value="relasi" />
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Nomor HP">
                                        <el-input placeholder="Nomor HP Ayah" v-model="ortu.ayah.hp"></el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Pekerjaan">
                                        <el-select placeholder="Pilih Pekerjaan" v-model="ortu.ayah.pekerjaan" filterable>
                                            <el-option v-for="pekerjaan in pekerjaans" :key="pekerjaan.id" :value="pekerjaan.nama" />
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-row :gutter="10">
                                <el-col>
                                    <el-form-item label="Alamat Ayah">
                                        <el-input type="textarea" placeholder="Alamat" v-model="ortu.ayah.alamat"></el-input>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                        </div>
                    </el-card>
                </el-col>
            </el-row>
            <!-- Data Ibu -->
            <el-row :gutter="10">
                <el-col>
                    <el-card sizr="small">
                        <template #header>
                            <h3 class="font-bold text-slate-700 flex items-center uppercase"><Icon icon="mdi:human-male" class="text-lg" /> Data Ibu</h3>
                        </template>
                        <div class="card-bod">
                            <el-row :gutter="10">
                                <el-col :span="12">
                                    <el-form-item label="NIK Ibu">
                                        <el-input placeholder="N.I.K. Ibu" v-model="ortu.ibu.nik"></el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="12">
                                    <el-form-item label="Nama Ibu">
                                        <el-input placeholder="Nama Ibu" v-model="ortu.ibu.nama"></el-input>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-row :gutter="10">
                                <el-col :span="8">
                                    <el-form-item label="Hubungan">
                                        <el-select placeholder="Pilih Hubungan" v-model="ortu.ibu.relasi">
                                            <el-option v-for="relasi in ['Ayah', 'Ibu', 'Wali']" :value="relasi" />
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Nomor HP">
                                        <el-input placeholder="Nomor HP Ibu" v-model="ortu.ibu.hp"></el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Pekerjaan">
                                        <el-select placeholder="Pilih Pekerjaan" v-model="ortu.ibu.pekerjaan" filterable>
                                            <el-option v-for="pekerjaan in pekerjaans" :key="pekerjaan.id" :value="pekerjaan.nama" />
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-row :gutter="10">
                                <el-col>
                                    <el-form-item label="Alamat Ayah">
                                        <el-input type="textarea" placeholder="Alamat" v-model="ortu.ibu.alamat"></el-input>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                        </div>
                    </el-card>
                </el-col>
            </el-row>
            <!-- Data Wali -->
            <el-row :gutter="10">
                <el-col>
                    <el-card sizr="small">
                        <template #header>
                            <h3 class="font-bold text-slate-700 flex items-center uppercase"><Icon icon="mdi:human-male" class="text-lg" /> Data Wali</h3>
                        </template>
                        <div class="card-bod">
                            <el-row :gutter="10">
                                <el-col :span="12">
                                    <el-form-item label="NIK Wali">
                                        <el-input placeholder="N.I.K. Wali" v-model="ortu.wali.nik"></el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="12">
                                    <el-form-item label="Nama Wali">
                                        <el-input placeholder="Nama Wali" v-model="ortu.wali.nama"></el-input>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-row :gutter="10">
                                <el-col :span="8">
                                    <el-form-item label="Hubungan">
                                        <el-select placeholder="Pilih Hubungan" v-model="ortu.wali.relasi">
                                            <el-option v-for="relasi in ['Ayah', 'Ibu', 'Wali']" :value="relasi" />
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Nomor HP">
                                        <el-input placeholder="Nomor HP Ayah" v-model="ortu.wali.hp"></el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Pekerjaan">
                                        <el-select placeholder="Pilih Pekerjaan" v-model="ortu.wali.pekerjaan" filterable>
                                            <el-option v-for="pekerjaan in pekerjaans" :key="pekerjaan.id" :value="pekerjaan.nama" />
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-row :gutter="10">
                                <el-col>
                                    <el-form-item label="Alamat Ayah">
                                        <el-input type="textarea" placeholder="Alamat" v-model="ortu.wali.alamat"></el-input>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                        </div>
                    </el-card>
                </el-col>
            </el-row>
        </el-form>
    </div>
    <template #footer>
        <div class="flex items-center justify-center p-2">
            <el-button type="primary" @click="simpan">Simpan</el-button>
        </div>
    </template>
</el-dialog>
</template>