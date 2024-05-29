<script setup>
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

const items = ref([
    {
        label: 'Dashboard',
        icon: 'monitor-dashboard',
        url: '/dashboard',
        children: []
    },
    {
        label: 'Data Utama',
        icon: 'database',
        url: '#',
        children: [
            {
                label: 'Data Sekolah',
                icon: 'city-variant',
                url: '/dashboard/sekolah',
            },
            {
                label: 'Data Guru',
                icon: 'account-tie',
                url: '/dashboard/guru',
            },
            {
                label: 'Data Rombel',
                icon: 'google-classroom',
                url: '#',
            },
            {
                label: 'Data Siswa',
                icon: 'human-child',
                url: '#',
            },
        ]
    },
    {
        label: 'Data Nilai',
        icon: 'numeric',
        url: '#',
        children: [
            {
                label: 'Input Nilai',
                icon: 'edit',
                url: '#',
            },
            {
                label: 'Ledger',
                icon: 'spreadsheet',
                url: '#',
            },
        ]
    },
    {
        label: 'Rapor',
        icon: 'report',
        url: '#',
        children: [
            {
                label: 'Data Periodik',
                icon: 'chart-line',
                url: '#',
            },
            {
                label: 'Cetak',
                icon: 'printer',
                url: '#',
            },
        ]
    },
    {
        label: 'Pengaturan',
        icon: 'settings',
        url: '#',
        children: [
            {
                label: 'Backup',
                icon: 'harddisk',
                url: '#',
            },
        ]
    },
])

const goto = (url) => {
    router.visit(url)
}
</script>

<template>
    <div class="p-2">
        <el-divider>Menu</el-divider>
        <ul>
            <template v-for="(item, i) in items" :key="i">
                <li v-if="item.children && item.children.length < 1">
                    <Link :href="item.url" :class="{ 'active' : $page.url === item.url}" class="flex items-center gap-1">
                        <Icon :icon="`mdi:${item.icon}`" />
                        <span>{{ item.label }}</span>
                    </Link>
                </li>
                <li v-else class="group">
                    <a :href="item.url" class="flex justify-between items-center gap-1 text-slate-600">
                        <span class="flex items-center gap-1">
                            <Icon :icon="`mdi:${item.icon}`" />
                            <span>{{ item.label }}</span>
                        </span>
                        <Icon icon="mdi:chevron-up" />
                    </a>
                    <ul class="pl-4 pt-1 ">
                        <li v-for="(sub, s) in item.children" :key="i+'-'+s">
                            <Link :href="sub.url" :class="{ 'active' : $page.url === sub.url}" class="flex items-center gap-1">
                                <Icon :icon="`mdi:${sub.icon}`" />
                                <span>{{ sub.label }}</span>
                                <!-- <Icon icon="mdi:chart" -->
                            </Link>
                        </li>
                    </ul>
                </li>
            </template>
        </ul>
    </div> 
</template>

<style>
.el-menu-item {
    height: 30px!important;
    line-height: 20px!important;
}
a.active {
    color: #786799;
}
</style>