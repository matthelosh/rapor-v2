<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'

import DashLayout from '@/Layouts/DashLayout.vue';
import { ElNotification } from 'element-plus';
const FormNilaiP5 = defineAsyncComponent(() => import('@/Components/Dashboard/P5/FormProyekP5.vue'))
const RaporP5 = defineAsyncComponent(() => import('@/Components/Dashboard/P5/RaporP5.vue'))

const mode = ref('list')
const loading = ref(false)
const page = usePage()
const props = defineProps({ proyeks: Array, dimensis: Array})
const role = computed(() => {
    return page.props.auth.user.roles[0]
})

const proyek = ref({})
const addProyek = () => {
    mode.value = 'form'
}

const simpanProyek = async() => {
    // console.log(proyek.value)
    router.post(route('dashboard.p5.proyek.store', {
        _query: {
            sekolah: page.props.sekolahs[0].npsn
        }
    }), proyek.value, {
        onStart: () => loading.value = true,
        onSuccess: () => {
            ElNotification({ title: 'Info', message: page.props.flash.message, type: 'success' })
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({ title: 'Error', message: errs[k], type: 'error' })
                }, 1000);
            })
        },
        onFinish: () => loading.value = false
    })
}


const selectedProyek = ref(null)
const inputNilaiP5 = (item) => {
    mode.value="input-nilai"
    selectedProyek.value = item
    // console.log(item)
}


const cetakRaporP5 = (item) => {
    mode.value = 'cetak-rapor'
    selectedProyek.value = item
    }

const closeMe = () => {
    mode.value = 'list'
    selectedProyek.value = null
}
</script>

<template>
<Head title="Data Proyek" />

<DashLayout>
    <template #header>
        Data Proyek
    </template>

    <el-card >
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Manajemen Proyek P5</h3>
                <div class="flex items-center">
                    <el-button v-if="mode=='list'" type="primary" @click="addProyek">Buat Proyek</el-button>
                    <el-button v-if="mode=='form'" type="danger" @click="mode = 'list'">Batal</el-button>
                </div>
            </div>
        </template>
        <template #default>
            <div v-if="mode=='list'">
                <el-row :gutter="20" style="margin-bottom: 20px;">
                    <template v-for="(proyek, p) in props.proyeks" :key="p">
                        <el-col :span="12" >
                            <el-card shadow="hover" class="mb-4">
                                <template #header>
                                    <div class="flex items-center justify-between">
                                        <h3>Proyek P5: {{ proyek.nama }} </h3>
                                    </div>
                                </template>
                                <template #default>
                                    <div class="flex items-center justify-between">
                                        <div class="label">
                                            <h1>{{ proyek.rombel.label }}</h1>
                                            <h1>Semester {{ proyek.semester.label }} Tahun Pelajaran {{ proyek.tapel.label }}</h1>
                                            <p>Deskripsi: {{ proyek.deskripsi }}</p>
                                        </div>
                                        <div class="items">
                                            <el-button-group>
                                                <el-button @click="inputNilaiP5(proyek)">Input Nilai</el-button>
                                                <el-button @click="cetakRaporP5(proyek)">Cetak Rapor</el-button>
                                            </el-button-group>
                                        </div>
                                    </div>
                                    <el-collapse>
                                        <el-collapse-item title="Lihat Dimensi dan Elemen">
                                            <el-table :data="proyek.apds" max-height="500">
                                                <el-table-column label="No" type="index"></el-table-column>
                                                <el-table-column label="Dimensi" prop="elemen.dimensi.dimensi" width="150"></el-table-column>
                                                <el-table-column label="Elemen" prop="elemen.teks" width="150"></el-table-column>
                                                <el-table-column label="Sub Elemen" prop="sub_elemen" width="150"></el-table-column>
                                                <el-table-column label="Target Pencapaian Akhir Fase" prop="teks"></el-table-column>
                                            </el-table>
                                        </el-collapse-item>
                                    </el-collapse>
                                </template>
                            </el-card>
                        </el-col>
                    </template>
                </el-row>
            </div>
            <div class="form " v-if="mode == 'form'">
                <h3 class="text-lg font-bold text-sky-700 text-center mb-4 uppercase">Formulir Proyek P5</h3>
                <el-form v-model="proyek" label-width="200">
                    <el-form-item label="Nama">
                        <el-input v-model="proyek.nama" placeholder="Nama Proyek"></el-input>
                    </el-form-item>
                    <el-form-item label="Deskripsi">
                        <el-input type="textarea" v-model="proyek.deskripsi" placeholder="Deskripsi Proyek"></el-input>
                    </el-form-item>
                    <el-form-item label="Rombel">
                        <el-select v-model="proyek.rombel_id" placeholder="Pilih Rombel">
                            <el-option v-for="(rombel, r) in page.props.rombels" :value="rombel.kode" :label="`${rombel.label}`"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Dimensi P5">
                        <el-select v-model="proyek.dimensis" placeholder="Pilih Dimensi" multiple value-key="id">
                            <el-option v-for="(dimensi, d) in dimensis" :key="dimensi.id" :value="dimensi" :label="`${dimensi.dimensi} (${dimensi.kode})`"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Elemen" v-if="proyek.dimensis && proyek.dimensis.length > 0" >
                        <el-collapse>
                            <template v-for="(dimensi, d) in proyek.dimensis" :key="d">
                                <el-collapse-item :title="`${dimensi.dimensi } (${dimensi.kode})`">
                                    <ul>
                                        <li v-for="elemen in dimensi.elemens">
                                            {{ elemen.teks }}
                                            <ul class="pl-8">

                                                <el-checkbox-group v-model="proyek.apd_ids">
                                                <li v-for="sub in elemen.apds.filter(apd => apd.fase == page.props.rombels.find(rombel => rombel.kode == proyek.rombel_id).fase)">
                                                    <el-checkbox :value="sub.id">
                                                        Fase {{ sub.fase }} | {{ sub.sub_elemen }} | {{ sub.teks }}
                                                    </el-checkbox>
                                                </li>
                                            </el-checkbox-group>
                                            </ul>
                                        </li>
                                    </ul>
                                </el-collapse-item>
                            </template>
                        </el-collapse>
                    </el-form-item>
                    <el-form-item >
                        <el-button type="primary" @click="simpanProyek">Simpan</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </template>
    </el-card>
   <FormNilaiP5 v-if="selectedProyek !== null && mode == 'input-nilai'" :proyek="selectedProyek" @close="closeMe" />
   <RaporP5 v-if="selectedProyek !== null && mode == 'cetak-rapor'" :proyek="selectedProyek" @close="closeMe" />
</DashLayout>
</template>
