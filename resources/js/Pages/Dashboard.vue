<script setup>
import {ref, computed, defineAsyncComponent } from 'vue'
import DashLayout from '@/Layouts/DashLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const Admin = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Admin.vue'))
const Ops = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Ops.vue'))
const Wali = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Wali.vue'))
const Mapel = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Mapel.vue'))
const Org = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Org.vue'))
const Siswa = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Siswa.vue'))

const page = usePage()
const role = computed(() => page.props.auth.roles[0])
const comp = computed(() => {
    // return (['superadmin', 'admin', 'admin_tp'].includes(role.value)) ? Admin : (role == 'ops' ? Ops : (role == 'guru_kelas' ? Wali : (role == 'siswa' ? Siswa : Mapel)))
    switch(role.value) {
        case ['superadmin', 'admin', 'admin_tp'].includes(role.value) :
            return Admin
            break;
        case 'ops':
            return Ops
            break
        case 'guru_kelas':
            return Wali
            break
        case "org":
            return Org
            break
        case "siswa":
            return Siswa
            break
        default:
            return Admin
            break
    }
})

const sekolahs = page.props.sekolahs
</script>

<template>
    <Head title="Dashboard" />

    <DashLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard {{role}}</h2>
        </template>

        <component :is="comp">
        </component>
    </DashLayout>
</template>
