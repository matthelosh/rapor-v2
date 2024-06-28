<script setup>
import {ref, computed, defineAsyncComponent } from 'vue'
import DashLayout from '@/Layouts/DashLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ElCard } from 'element-plus'
import { Icon } from '@iconify/vue'
import { capitalize } from '@/helpers/String.js'

const page = usePage()
const role = page.props.auth.roles[0]
const comp = computed(() => {
    return role == 'admin' ? 'Admin' : 'Ops'
})
const is = computed(() => {
    return defineAsyncComponent(() => import('../Components/Dashboard/Home/'+comp.value+'.vue'))
})

const sekolahs = page.props.sekolahs
</script>

<template>
    <Head title="Dashboard" />

    <DashLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <component :is="is" class="page">
        </component>
    </DashLayout>
</template>
