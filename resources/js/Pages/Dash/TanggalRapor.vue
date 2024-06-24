<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import DashLayout from '@/Layouts/DashLayout.vue'
import { Icon } from '@iconify/vue'
import { ElNotification} from 'element-plus'
import dayjs from 'dayjs'
import 'dayjs/locale/id'

const page = usePage()

const formTanggal = ref(false)
const newTanggal = () => {
    formTanggal.value = true
}

const closeForm = () => {
    formTanggal.value = false
    tanggal.value = {}
}

const tanggal = ref({})

const simpan = async() => {
    router.post(route('dashboard.rapor.tanggal.store', {
        _query: {
            sekolahId: page.props.sekolahs[0].npsn
        }
    }), {data: tanggal.value}, {
        onSuccess: page => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
            closeForm()
        }, onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500);
            })
        }
    })
}

const hapus = async(id) => {
    router.delete(route('dashboard.rapor.tanggal.destroy', {id: id}), {
        onSuccess: page => {
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
        }, onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500);
            })
        }
    })
}

const edit = (item) => {
    tanggal.value = item
    formTanggal.value = true
}
</script>

<template>
<Head title="Pengaturan Tanggal Rapor" />
<DashLayout>
    <template #header>
        <span class="uppercase">Tanggal Rapor {{ page.props.sekolahs[0].nama }}</span>
    </template>
    <el-card>
        <template #header>
            <div class="card-header flex items-center justify-between">
                <span class="card-title font-bold text-slate-700 text-lg flex items-center">
                    <Icon icon="mdi:calendar" />
                    Pengaturan tanggal Rapor
                </span>
                <div class="card-header--items flex items-center">
                    <el-button type="primary" class="flex items-center gap-1" @click="newTanggal">
                        <Icon icon="mdi:calendar-plus" />
                        Baru
                    </el-button>
                </div>
            </div>
        </template>
        <div class="card-body p-2">
            <el-table :data="page.props.tanggals">
                <el-table-column label="Tipe Rapor">
                    <template #default="scope">
                        {{ scope.row.tipe.toUpperCase() }}
                    </template>
                </el-table-column>
                <el-table-column label="Tapel">
                    <template #default="scope">
                        {{ scope.row.tahun.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Semester">
                    <template #default="scope">
                        {{ scope.row.sem.label }}
                    </template>
                </el-table-column>
                <el-table-column label="Tanggal">
                    <template #default="scope">
                        {{ dayjs(scope.row.tanggal).locale('id').format('DD MMMM YYYY') }}
                    </template>

                </el-table-column>
                <el-table-column label="Opsi">
                    <template #default="scope">
                        <span class="flex items-center">
                            <el-button circle type="warning" size="small" @click="edit(scope.row)">
                                <Icon icon="mdi:edit" />
                            </el-button>
                            <el-popconfirm @confirm="hapus(scope.row.id)" title="Hapus Tanggal ini?">
                                <template #reference>
                                    <el-button circle type="danger" size="small">
                                        <Icon icon="mdi:delete" />
                                    </el-button>
                                </template>
                            </el-popconfirm>
                        </span>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </el-card>
</DashLayout>
<el-dialog v-model="formTanggal" :show-close="false" width="500">
    <template #header>
        <div class="flex justify-between items-center">
            <span class="title">Tanggal Rapor</span>
            <el-button circle type="danger" @click="closeForm">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </template>
    <div class="dialog-body">
        <el-form v-model="tanggal" label-position="top">
            <el-form-item label="Tahun Pelajaran">
                <el-select v-model="tanggal.tapel" placeholder="Pilih Tahun Pelajaran">
                    <el-option v-for="tapel in page.props.tapels" :key="tapel.id" :value="tapel.kode" :label="tapel.label"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Semester">
                <el-select v-model="tanggal.semester" placeholder="Pilih Semester">
                    <el-option v-for="sem in ['1', '2']" :key="sem" :value="sem" :label="`Semester ${sem}`"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Tipe Rapor">
                <el-select v-model="tanggal.tipe" placeholder="Pilih Tipe Rapor">
                    <el-option v-for="tipe in ['pts', 'pas']" :key="tipe" :value="tipe" :label="tipe.toUpperCase()"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Tanggal Penerimaan">
                <el-date-picker
                    v-model="tanggal.tanggal"
                    type="date"
                    placeholder="Pilih Tanggal Penerimaan"
                    value-format="YYYY-MM-DD"
                    format="YYYY-MM-DD"
                />
            </el-form-item>
        </el-form>
    </div>
    <template #footer>
        <span class="flex justify-end">
            <el-button type="primary" @click="simpan">Simpan</el-button>
        </span>
    </template>
</el-dialog>
</template>