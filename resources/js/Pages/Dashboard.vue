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
    if (['superadmin', 'admin', 'admin_tp'].includes(role.value)) {
        return Admin;
    } else if (role.value == "ops") {
        return Ops;
    } else if(role.value == "guru_kelas") {
        return Wali;
    } else if (role.value == "siswa") {
        return Siswa;
    } else {
        return Mapel;
    }
    return (['superadmin', 'admin', 'admin_tp'].includes(role.value)) ? Admin : (role == 'ops' ? Ops : (role == 'guru_kelas' ? Wali : (role == 'siswa' ? Siswa : Mapel)))
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
