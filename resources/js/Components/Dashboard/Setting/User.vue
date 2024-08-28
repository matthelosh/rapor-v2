<script setup>
import { ref, computed } from 'vue'
import { Icon } from '@iconify/vue';
import { router } from '@inertiajs/vue3';
import { ElNotification } from 'element-plus';
const props = defineProps({
    users: Array,
    roles: Array,
    permissions: Array
})

const search = ref(null)
const filteredSearch  = computed(() => {
    return !search.value ? props.users : props.users.filter(user => user.userable ? user.userable.nama.toLowerCase().includes(search.value.toLowerCase()) : user.name.toLowerCase().includes(search.value.toLowerCase()))
})

const showPassword = ref(false)

const loading = ref(false)
const newUser = ref({})
const formUser = ref(false)
const addNew = () => {
    formUser.value = true
}

const closeForm = () => {
    formUser.value = false
}

const simpan  = async() => {
    
    router.post(route('dashboard.user.store'), newUser.value, {
        onStart: () => loading.value = true,
        onSuccess: () => {
            loading.value = false
            router.reload({only: ['users']})
            newUser.value = {}
            formUser.value = false
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type:'error'})
                }, 500)
            })
            loading.value = false
        }
    })
}

const assignPermission = async(user) => {
    await router.post(route('dashboard.user.permission.assign'), user, {
        onStart: () => loading.value = true,
        onSuccess: () => {
            loading.value = false
            router.reload({only: ['users']})
            newUser.value = {}
            formUser.value = false
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type:'error'})
                }, 500)
            })
            loading.value = false
        }
    })
}
</script>

<template>
    <el-card style="margin-bottom: 20px;">
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Data Pengguna</h3>
                <div class="toolbar-items flex items-center gap-2">
                    <el-input v-model="search" placeholder="Cari User" clearable size="small" >
                        <template #suffix>
                            <Icon icon="mdi:account-search" />
                        </template>
                    </el-input>
                    <el-button type="primary" size="small" @click="addNew">Buat User</el-button>
                </div>
            </div>
        </template>
        <template #default>
            <div class="body">
                <el-table :data="filteredSearch" max-height="600">
                    <el-table-column label="#" type="index"></el-table-column>
                    <el-table-column label="Username" prop="name"></el-table-column>
                    <el-table-column label="Email" prop="email"></el-table-column>
                    <el-table-column label="Nama">
                        <template #default="scope">
                            <div>
                                {{ scope.row.userable?.nama }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Lembaga">
                        <template #default="scope">
                            <div>
                                {{ scope.row.userable?.sekolahs[0]?.nama }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Jabatan">
                        <template #default="scope">
                            <div>
                                {{ scope.row.userable?.jabatan }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Role">
                        <template #default="scope">
                            <div>
                                {{ scope.row.roles.map(role => role.name) }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Permission">
                        <template #default="scope">
                            <div>
                                {{ scope.row.permissions }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Kontak">
                        <template #default="scope">
                            <div>
                                {{ scope.row.userable?.hp }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Opsi" fixed="right">
                        <template #default="scope">
                            <div class="flex items-center gap-1">
                                <el-popover trigger="click">
                                    <template #reference>
                                        <el-button circle type="primary" size="small">
                                            <Icon icon="mdi:account-edit" />
                                        </el-button>
                                    </template>
                                    <template #default>
                                        <h3>Berikan Ijin</h3>
                                        <el-select v-model="scope.row.permissions" filterable multiple @change="assignPermission(scope.row)">
                                            <el-option v-for="(permission, p) in props.permissions" :value="permission.name"></el-option>
                                        </el-select>
                                    </template>
                                </el-popover>
                                <el-button circle type="warning" size="small">
                                    <Icon icon="mdi:account-reactivate" />
                                </el-button>
                                <el-button circle type="danger" size="small">
                                    <Icon icon="mdi:account-cancel" />
                                </el-button>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </template>
    </el-card>
    <el-dialog v-model="formUser"  style="padding:0;" :show-close="false">
        <template #header>
            <div class="flex items-center justify-between py-4 h-full">
                <h3>Form User</h3>
                <el-button circle type="danger" @click="closeForm" size="small">
                    <Icon icon="mdi:close" />
                </el-button>
            </div>
        </template>
        <div class="dialog-body p-4">
            <el-form v-model="newUser" label-width="180" v-loading="loading">
                <el-form-item label="Username">
                    <el-input v-model="newUser.name" placeholder="Isikan Username"></el-input>
                </el-form-item>
                <el-form-item label="Email">
                    <el-input type="email" v-model="newUser.email" placeholder="Isikan Email"></el-input>
                </el-form-item>
                <el-form-item label="Password">
                    <el-input :type="showPassword ? 'text': 'password'" v-model="newUser.password" placeholder="Isikan Password">
                        <template #suffix>
                            <Icon :icon="`mdi:${showPassword ? 'eye-off' : 'eye'}`" @click="showPassword = !showPassword" class="hover:cursor-pointer" />
                        </template>
                    </el-input>
                </el-form-item>
                <el-form-item label="Role">
                    <el-select v-model="newUser.role" placeholder="Role Pengguna" filterable>
                        <el-option v-for="(role, r) in props.roles" :key="r" :value="role.name"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item >
                    <el-button type="primary" @click="simpan">Simpan</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-dialog>
</template>
<style>
.el-dialog__header {
    margin-right: 0!important;
}
</style>