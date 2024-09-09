<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3';

import DashLayout from '@/Layouts/DashLayout.vue';
import { ElNotification } from 'element-plus';

const LembarSoal = defineAsyncComponent(() => import('@/Components/Dashboard/Asesmen/LembarSoal.vue'))

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
    let data = asesmen.value
    data.sekolah_id = page.props.sekolahs[0].npsn
    data.tapel = page.props.periode.tapel.kode
    data.semester = page.props.periode.semester.kode
    data.guru_id = page.props.auth.user.userable.nip
    router.post(route('dashboard.asesmen.store'), data, {
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

const selectedAsesmen = ref(null)
const showLembarSoal = (item) => {
    selectedAsesmen.value = item
    mode.value = 'lembar-soal'
}

const closeLembarSoal = () => {
    selectedAsesmen.value = null
    mode.value = 'list'
}

const edit = (item) => {
    asesmen.value = item
    mode.value = 'form'
}

const hapus = async(item) => {
    await router.delete(route('dashboard.asesmen.destroy', {id: item.id}), {
        onStart: () => loading.value = true,
        onSuccess: () => {
            router.reload({only: ['soals']})
            mode.value = 'list'
            asesmen.value = {}
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

</script>

<template>
<Head title="Asesmen" />
<DashLayout title="Asesmen">
    <template #header>Asesmen</template>
    <el-card>
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Data Asesmen</h3>
                <div class="flex items-center gap-1">
                    <el-button type="primary" size="small" @click="addAsesmen" :disabled="!canAddAsesmen" v-if="mode == 'list'">Buat Asesmen</el-button>
                    <el-button type="danger" size="small" @click="mode = 'list'" :disabled="!canAddAsesmen" v-if="mode == 'form'">Tutup</el-button>
                </div>
            </div>
        </template>
        <div class="list" v-if="mode == 'list'">
            <el-table :data="asesmens">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="Kode" prop="kode"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <el-table-column label="Kelas" width="100">
                    <template #default="scope">
                        {{ scope.row.rombel.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Mapel" >
                    <template #default="scope">
                        {{ scope.row.mapel.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Semester" width="100">
                    <template #default="scope">
                        {{ scope.row.semester.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Jml Soal" width="100">
                    <template #default="scope">
                        {{ scope.row.soals?.length }}
                    </template>
                </el-table-column>
                <el-table-column label="Opsi" width="208" fixed="right">
                    <template #default="{row}">
                        <div class="flex items-center">
                            <el-button-group size="small">
                                <el-button @click="edit(row)">Edit</el-button>
                                <el-button @click="showLembarSoal(row)">Atur Soal</el-button>
                                <el-popconfirm title="Hapus Asesmen?" confirm-text="OK" @confirm="hapus(row)">
                                    <template #reference>
                                        <el-button type="danger">Hapus</el-button>
                                    </template>
                                </el-popconfirm>
                            </el-button-group>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <div class="form md:w-[60%] bg-slate-100 shadow p-2 md:p-4 mx-auto" v-if="mode == 'form'">
            <h1 class="text-lg font-bold text-sky-700 text-center uppercase mb-4">Formulir Asesmen</h1>
            <el-form v-model="asesmen" label-position="top" v-loading="loading" :rules="rules">
                <el-row :gutter="20">
                    <!-- <el-col :span="6" :xs="24">
                        <el-form-item label="Kode">
                            <el-input :input-style="{border: 'red', background: '#ffefee', outline: 'red'}" v-model="asesmen.kode" placeholder="Kode Asesmen" :readonly="true"></el-input>
                        </el-form-item>
                    </el-col> -->
                    <el-col :span="8" :xs="24">
                        <el-form-item label="Nama Asesmen">
                            <el-input v-model="asesmen.nama" placeholder="Nama Asesmen"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="10" :xs="24">
                        <el-form-item label="Petunjuk Pengerjaan">
                            <el-input type="textarea" autosize v-model="asesmen.deskripsi" placeholder="Keterangan" :readonly="false"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="6" :xs="24">
                        <el-form-item label="Mapel">
                            <el-select v-model="asesmen.mapel_id" placeholder="Mapel" :readonly="false">
                                <el-option v-for="(mapel, m) in page.props.sekolahs[0].mapels" :value="mapel.kode" :label="mapel.label"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="6" :xs="24">
                        <el-form-item label="Rombel">
                            <el-select v-model="asesmen.rombel_id" placeholder="Rombel" :readonly="false">
                                <el-option v-for="(rombel, r) in page.props.sekolahs[0].rombels" :value="rombel.kode" :label="rombel.label"></el-option>
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
    <LembarSoal v-if="selectedAsesmen !== null" :selectedAsesmen="selectedAsesmen" @close="closeLembarSoal" />
</DashLayout>
</template>

<style>
.el-card__body {
    padding: 0!important;
}

@media screen and(min-width: 414px) {
    .el-card__body {
        padding: 20px;
    }
}
</style>