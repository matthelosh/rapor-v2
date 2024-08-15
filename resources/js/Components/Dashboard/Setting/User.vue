<script setup>
import { ref, computed } from 'vue'
import { Icon } from '@iconify/vue';
const props = defineProps({
    users: Array
})

const search = ref(null)
const filteredSearch  = computed(() => {
    return !search.value ? props.users : props.users.filter(user => user.userable ? user.userable.nama.toLowerCase().includes(search.value.toLowerCase()) : user.name.toLowerCase().includes(search.value.toLowerCase()))
})
</script>

<template>
    <el-card style="margin-bottom: 20px;">
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Data Pengguna</h3>
                <div class="toolbar-items flex items-center">
                    <el-input v-model="search" placeholder="Cari User" clearable    >
                        <template #suffix>
                            <Icon icon="mdi:account-search" />
                        </template>
                    </el-input>
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
                                <el-button circle type="primary" size="small">
                                    <Icon icon="mdi:account-edit" />
                                </el-button>
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
</template>