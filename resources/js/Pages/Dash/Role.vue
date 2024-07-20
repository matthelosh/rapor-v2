<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import DashLayout from '@/Layouts/DashLayout.vue'
import { findIndex } from 'lodash';
import { ElNotification } from 'element-plus';

const page = usePage()
const roles = ref([])
const role = ref({})
const permissions = ref([])
const permission = ref({})

const assignPermission = async(role) => {
    const index = findIndex(roles.value, (item) => item.id === role.id, 0)
    // console.log(roles.value[index])
    router.post(route('dashboard.role.permission.assign'), 
                {role: roles.value[index]}, {
                    onSuccess: page => {
                        ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
                        router.reload({only: ['roles']})
                    }, onError: errs => {
                        Object.keys(errs).forEach(k => {
                            setTimeout(() => {
                                ElNotification({title: 'Error', message: errs[k], type: 'error'})
                            }, 500)
                        })
                    }
                })
}

const saveRole = async() => {
    router.post(route('dashboard.role.store'), {role: role.value}, {
        onSuccess: page => {
                ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
                router.reload({only: ['roles']})
            }, onError: errs => {
                Object.keys(errs).forEach(k => {
                    setTimeout(() => {
                        ElNotification({title: 'Error', message: errs[k], type: 'error'})
                    }, 500)
                })
            }
    })
}
const savePermission = async() => {
    router.post(route('dashboard.permission.store'), {permission: permission.value}, {
        onSuccess: page => {
                ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
                router.reload({only: ['roles']})
            }, onError: errs => {
                Object.keys(errs).forEach(k => {
                    setTimeout(() => {
                        ElNotification({title: 'Error', message: errs[k], type: 'error'})
                    }, 500)
                })
            }
    })
}

onBeforeMount(async() => {
    page.props.roles.forEach(role => {
        role.permissions = role.permissions.map(per => per.name)
        roles.value.push(role)
    })
})
</script>

<template>
    <Head title="Pengaturan peran dan ijin user" />
    <DashLayout>
        <template #header>Admin</template>
        <el-card >
            <template #header>
                <div class="toolbar flex items-center justify-between">
                    <h3 class="flex items-center gap-1">
                        <Icon icon="mdi:account-settings-variant" />
                        <span class="font-bold text-slate-600 text-lg">Peran dan Ijin User</span>
                    </h3>
                    <div class="toolbar-items flex justify-end items-center">
                        <el-popover title="Tambah Peran" trigger="click" width="400">
                            <template #reference >
                                <el-button type="primary" size="small">Tambah Peran</el-button>
                            </template>
                            <template #default>
                                <el-form label-position="top">
                                    <el-form-item label="Nama Peran">
                                        <el-input v-model="role.name" placeholder="Nama Peran" size="small"></el-input>
                                    </el-form-item>
                                    <el-form-item >
                                        <el-button @click="saveRole" size="small" type="primary">Simpan</el-button>
                                    </el-form-item>
                                </el-form>
                            </template>
                        </el-popover>
                        <el-popover title="Tambah Ijin" trigger="click" width="400">
                            <template #reference >
                                <el-button type="primary" size="small">Tambah Ijin</el-button>
                            </template>
                            <template #default>
                                <el-form label-position="top">
                                    <el-form-item label="Nama Ijin">
                                        <el-input v-model="permission.name" placeholder="Nama Ijin" size="small"></el-input>
                                    </el-form-item>
                                    <el-form-item >
                                        <el-button @click="savePermission" size="small" type="primary">Simpan</el-button>
                                    </el-form-item>
                                </el-form>
                            </template>
                        </el-popover>
                    </div>
                </div>
            </template>
            <div class="card-body">
                <el-row>
                    <el-col>
                        <el-table :data="roles">
                            <el-table-column label="#" type="index" width="60" />
                            <el-table-column label="Peran" prop="name" width="200"></el-table-column>
                            <el-table-column label="Permission">
                                <template #default="scope">
                                    {{ scope.row.permissions }}
                                </template>
                            </el-table-column>
                            <el-table-column label="Ijin" width="400">
                                <template #default="scope">
                                    <el-select v-model="roles[scope.$index]['permissions']" multiple filterable placeholder="Pilih Ijin untuk Pengguna" collapse-tags collapse-tags-tooltip :max-collapse-tags="5" style="width: 300px">
                                        <el-option v-for="(per, p) in page.props.permissions" :key="p" :value="per.name" :label="per.name"></el-option>
                                        <!-- <template #tag>
                                            <span class="w-[100px]">
                                                <el-tag v-for="name in roles[scope.$index]['permissions']">{{ name }}</el-tag>
                                            </span>
                                        </template> -->
                                    </el-select>
                                </template>
                            </el-table-column>
                            <el-table-column label="Opsi" width="100">
                                <template #default="scope">
                                    <span>
                                        <el-button type="primary" size="small" @click="assignPermission(scope.row)">Simpan</el-button>
                                    </span>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-col>
                </el-row>
            </div>
        </el-card>
    </DashLayout>
</template>

<style>
    .el-popper.is-light {
        max-width: 400px;
        /* background: #efefef!important; */
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
</style>