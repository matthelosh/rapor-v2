<script setup>
import { defineAsyncComponent, ref } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';

import DashLayout from '@/Layouts/DashLayout.vue';

const AnggotaOrganisasi = defineAsyncComponent(() => import('@/Components/Dashboard/Organisasi/AnggotaOrganisasi.vue'))

const page = usePage()
const props = defineProps({orgs: Array})
const pageTitle = ref('Data Organisasi')

const showFormAnggota = ref(false)
const selectedOrg = ref(null)

const closeFormAnggota = () => {
    selectedOrg.value = null
    showFormAnggota.value = false
}

const openFormAnggota = (org) => {
    selectedOrg.value = org
    showFormAnggota.value = true
}

</script>

<template>
<Head :title="pageTitle" />
<DashLayout>
    <template #header>
        {{ pageTitle }}
    </template>
    <el-card>
        <template #header>
            <div class="flex items-center justify-between">
                <h1>{{ pageTitle }}</h1>
            </div>
        </template>
        <div>
            <el-table :data="props.orgs">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <el-table-column label="Deskripsi" prop="deskripsi"></el-table-column>
                <el-table-column label="Anggota">
                    <template #default="{row}">
                        {{ row.members?.length }}
                    </template>
                </el-table-column>
                <el-table-column label="Opsi">
                    <template #default="{row}">
                        <el-button-group size="small">
                            <el-button>Edit</el-button>
                            <el-button @click="openFormAnggota(row)">Anggota</el-button>
                            <el-button type="danger">Hapus</el-button>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </el-card>
    <AnggotaOrganisasi v-if="showFormAnggota" :open="showFormAnggota" :org="selectedOrg" @close="closeFormAnggota" />
</DashLayout>
</template>