<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import DashLayout from '@/Layouts/DashLayout.vue'
import { findIndex } from 'lodash';
import { ElNotification } from 'element-plus';

const page = usePage()
const roles = ref([])
const permissions = ref([])

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
                </div>
                <div class="toolbar-items flex justify-end items-ceter">
                    
                </div>
            </template>
            <div class="card-body">
                <el-row>
                    <el-col>
                        <h3 class="text-lg font-bold text-sky-700">Peran dan Ijin</h3>
                        <el-table :data="roles">
                            <el-table-column label="#" type="index" />
                            <el-table-column label="Peran" prop="name"></el-table-column>
                            <el-table-column label="Permission">
                                <template #default="scope">
                                    {{ scope.row.permissions }}
                                </template>
                            </el-table-column>
                            <el-table-column label="Ijin">
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
                            <el-table-column label="Opsi">
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
        max-width: 300px;
        /* background: #efefef!important; */
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }
</style>