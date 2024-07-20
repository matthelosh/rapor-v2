<script setup>
import { ref, computed } from 'vue'
import { usePage, Head, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

const page = usePage()

const data = computed(() => page.props.data)

const onTapelChange = async(e, item) => {
    await router.post(route('dashboard.tapel.toggle', {id: item.id}), {status: e, _method: 'put'}, {
        onSuccess: page => {
            ElNotification({title: "Info", message: page.props.flash.message, type: 'success'})
            router.reload({only:['tapels']})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: "Error", message: errs[k], type: 'error'})
                }, 500);
            })
        }
    })
}
const onSemesterChange = async(e, item) => {
    await router.post(route('dashboard.semester.toggle', {id: item.id}), {status: e, _method: 'put'}, {
        onSuccess: page => {
            ElNotification({title: "Info", message: page.props.flash.message, type: 'success'})
            router.reload({only:['semester']})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: "Error", message: errs[k], type: 'error'})
                }, 500);
            })
        }
    })
}
const tapel = ref({})

const simpanTapel = async() => {
    router.post(route('dashboard.tapel.store'), {data: tapel.value}, {
        onSuccess: page => {
            ElNotification({title: "Info", message: page.props.flash.message, type: 'success'})
            router.reload({only:['tapels']})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: "Error", message: errs[k], type: 'error'})
                }, 500);
            })
        }
    })
} 
const semester = ref({})

const simpanSemester = async() => {
    router.post(route('dashboard.semester.store'), {data: semester.value}, {
        onSuccess: page => {
            ElNotification({title: "Info", message: page.props.flash.message, type: 'success'})
            router.reload({only:['semester']})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: "Error", message: errs[k], type: 'error'})
                }, 500);
            })
        }
    })
} 
</script>

<template>
    <!-- <div>{{page.props.data}}</div> -->
    <div class="flex gap-2">
        <el-card class="w-[50%] h-[600px]">
            <template #header>
                <span>Data Sekolah</span>
            </template>
            <el-table :data="data.sekolahs" height="550" size="small">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="NPSN" prop="npsn"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <el-table-column label="Alamat" prop="alamat"></el-table-column>
                <el-table-column label="KS" prop="ks.nama"></el-table-column>
            </el-table>
        </el-card>
        <el-card class="w-[25%] h-[350px]">
            <template #header>
                <div class="flex items-center justify-between">
                    <span>Tahun Pelajaran</span>
                    <el-popover trigger="click" width="250">
                        <template #reference>
                            <el-button circle type="primary" size="small">
                                <Icon icon="mdi-plus" />
                            </el-button>
                        </template>
                        <div class="form-tapel">
                            <el-form v-model="tapel" label-position="top" size="small">
                                <el-form-item label="Kode">
                                    <el-input v-model="tapel.kode" placeholder="Kode Tapel" size="small"></el-input>
                                </el-form-item>
                                <el-form-item label="Label">
                                    <el-input v-model="tapel.label" placeholder="Label" size="small"></el-input>
                                </el-form-item>
                                <el-form-item label="Deskripsi">
                                    <el-input v-model="tapel.deskripsi" placeholder="Deskripsi" type="textarea" size="small"></el-input>
                                </el-form-item>
                                <el-button class="mx-auto" type="primary" @click="simpanTapel">Simpan</el-button>
                            </el-form>
                        </div>
                    </el-popover>
                </div>
            </template>
            <el-table :data="data.tapels" height="300" size="small">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="Kode" prop="kode"></el-table-column>
                <el-table-column label="Label" prop="label"></el-table-column>
                <el-table-column label="Status" align="center">
                    <template #default="scope">
                        <el-switch v-model="scope.row.is_active" size="small" :active-value="1" :inactive-value="0" @change="onTapelChange($event, scope.row)"></el-switch>
                    </template>
                </el-table-column>
            </el-table>
        </el-card>
        <el-card class="w-[25%] h-[200px]">
            <template #header>
                <div class="flex items-center justify-between">
                    <span>Semester</span>
                    <el-popover trigger="click" width="250">
                        <template #reference>
                            <el-button circle type="primary" size="small">
                                <Icon icon="mdi-plus" />
                            </el-button>
                        </template>
                        <div class="form-semester">
                            <el-form v-model="semester" label-position="top" size="small">
                                <el-form-item label="Kode">
                                    <el-input v-model="semester.kode" placeholder="Kode Semester" size="small"></el-input>
                                </el-form-item>
                                <el-form-item label="Label">
                                    <el-input v-model="semester.label" placeholder="Label" size="small"></el-input>
                                </el-form-item>
                                <el-form-item label="Deskripsi">
                                    <el-input v-model="semester.deskripsi" placeholder="Deskripsi" type="textarea" size="small"></el-input>
                                </el-form-item>
                                <el-button class="mx-auto" type="primary" @click="simpanSemester">Simpan</el-button>
                            </el-form>
                        </div>
                    </el-popover>
                </div>
            </template>
            <el-table :data="data.semester" height="200" size="small">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="Kode" prop="kode"></el-table-column>
                <el-table-column label="Label" prop="label"></el-table-column>
                <el-table-column label="Status" align="center">
                    <template #default="scope">
                        <!-- {{ scope.row.is_active }} -->
                        <el-switch v-model="scope.row.is_active" size="small" :active-value="1" :inactive-value="0" @change="onSemesterChange($event, scope.row)"></el-switch>
                    </template>
                </el-table-column>
            </el-table>
        </el-card>
    </div>
</template>