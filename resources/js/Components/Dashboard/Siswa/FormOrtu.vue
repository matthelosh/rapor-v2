<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

const page = usePage()
const props = defineProps({ siswa: Object, open: Boolean })
// const emit = defineEmits(['close'])

const ortu = ref({
    siswa_id: '',
    ayah: {
        nik: '',
        nama: '',
        relasi:'Ayah',
        alamat:'',
        rt:'',
        rw:'',
        desa:'',
        kode_pos:'',
        kecamatan:'',
        kabupaten:'',
        hp:''
    },
    ibu: {},
    wali: {}
})

const fields = ref([
    
]);

onBeforeMount(() => {
    // ortu.value = props.siswa.or
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
                                <el-col :span="12">
                                    <el-form-item label="Hubungan">
                                        <el-select placeholder="Pilih Hubungan" v-model="ortu.ayah.relasi">
                                            <el-option v-for="relasi in ['Ayah', 'Ibu', 'Wali']" :value="relasi" />
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="12">
                                    <el-form-item label="Nomor HP">
                                        <el-input placeholder="Nomor HP Ayah" v-model="ortu.ayah.hp"></el-input>
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
            
        </el-form>
    </div>
</el-dialog>
</template>