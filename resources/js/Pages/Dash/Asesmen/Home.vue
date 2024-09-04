<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3';

import DashLayout from '@/Layouts/DashLayout.vue';
import { ElNotification } from 'element-plus';

const page = usePage()
const mode = ref('list')
const loading = ref(false)
defineProps({canAddAsesmen: Boolean})

const asesmens = computed(() => page.props.asesmens)

const asesmen = ref({})
const addAsesmen = () => {
    mode.value = 'form'
}

const simpanAsesmen = async () => {
    router.post(route('dashboard.asesmen.store'), asesmen.value, {
        onStart: () => loading.value = true,
        onSuccess: () => {
            router.reload({only: ['asesmens']})
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
        }, 
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500);
            })
        },
        onFinish: () => loading.value = false
    })
}

const rules = ref({
    nama: [
        { required: true}
    ]
})

</script>

<template>
<Head title="Asesmen" />
<DashLayout title="Asesmen">
    <template #header>Asesmen</template>
    <el-card>
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Data Asesmen {{ canAddAsesmen }}</h3>
                <div class="flex items-center gap-1">
                    <el-button type="primary" size="small" @click="addAsesmen" :disabled="!canAddAsesmen">Buat Asesmen</el-button>
                </div>
            </div>
        </template>
        <div class="list" v-if="mode == 'list'">
            <el-table :data="asesmens">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <el-table-column label="Kelas" >
                    <template #default="scope">
                        {{ scope.row.rombel.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Mapel" >
                    <template #default="scope">
                        {{ scope.row.mapel.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Semester" >
                    <template #default="scope">
                        {{ scope.row.semester }}
                    </template>
                </el-table-column>
                <el-table-column label="Jml Soal" >
                    <template #default="scope">
                        {{ scope.row.soals?.length }}
                    </template>
                </el-table-column>
                <el-table-column label="Opsi" >
                    <template #default="scope">
                        
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <div class="form w-[60%] bg-slate-100 shadow p-4 mx-auto" v-if="mode == 'form'">
            <h1 class="text-lg font-bold text-sky-700 text-center uppercase mb-4">Formulir Asesmen</h1>
            <el-form v-model="asesmen" label-position="top" v-loading="loading" :rules="rules">
                <el-row :gutter="20">
                    <el-col :span="6" :xs="24">
                        <el-form-item label="Kode">
                            <el-input :input-style="{border: 'red', background: '#ffefee', outline: 'red'}" v-model="asesmen.kode" placeholder="Kode Asesmen" :readonly="true"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8" :xs="24">
                        <el-form-item label="Nama Asesmen">
                            <el-input v-model="asesmen.nama" placeholder="Nama Asesmen"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="10" :xs="24">
                        <el-form-item label="Deskripsi Asesmen">
                            <el-input type="textarea" autosize v-model="asesmen.deskripsi" placeholder="Keterangan" :readonly="false"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="6" :xs="24">
                        <el-form-item label="Mapel">
                            <el-select v-model="asesmen.mapel_id" placeholder="Mapel" :readonly="false">

                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="6" :xs="24">
                        <el-form-item label="Tanggal">
                            <el-date-picker v-model="asesmen.tanggal" placeholder="Tanggal Pelaksanaan" :value-format="'YYYY-MM-DD'" ></el-date-picker>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2" :xs="24">
                        <el-form-item label="Mulai">
                            <el-input type="time" v-model="asesmen.mulai" placeholder="Mulai" ></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2" :xs="24">
                        <el-form-item label="Selesai">
                            <el-input type="time" v-model="asesmen.selesai" placeholder="Selesai"  ></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="4" :xs="24">
                        <el-form-item label="Jenis Asesmen">
                            <el-select v-model="asesmen.jenis" placeholder="Jenis Asesmen">
                                <el-option v-for="jenis in ['uh', 'pts', 'pas']" :value="jenis" :label="jenis.toUpperCase()"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter=20 justify="center">
                    <el-button type="primary" @click="simpanAsesmen">Simpan</el-button>
                </el-row>
            </el-form>
        </div>
    </el-card>
</DashLayout>
</template>