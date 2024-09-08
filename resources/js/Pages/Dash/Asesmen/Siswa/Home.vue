<script setup>
import { computed } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import DashLayout from '@/Layouts/DashLayout.vue';

const page = usePage()

</script>

<template>
    <Head title="Asesmen" />
    <DashLayout>
        <template #header>
            Daftar Asesmen
        </template>

        {{ page.props.asesmens }}
    </DashLayout>

</template>