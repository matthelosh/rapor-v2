<script setup>
import {ref, computed, defineAsyncComponent } from 'vue'
import DashLayout from '@/Layouts/DashLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const Admin = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Admin.vue'))
const Ops = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Ops.vue'))
const Wali = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Wali.vue'))
const Mapel = defineAsyncComponent(() => import('@/Components/Dashboard/Home/Mapel.vue'))

const page = usePage()
const role = page.props.auth.roles[0]
const comp = computed(() => {
    return role == 'admin' ? Admin : (role == 'ops' ? Ops : (role == 'guru_kelas' ? Wali : Mapel))
})

const sekolahs = page.props.sekolahs
</script>

<template>
    <Head title="Dashboard" />

    <DashLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <component :is="comp">
        </component>
    </DashLayout>
</template>
