<script setup>
import { ref, computed } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

const page = usePage()
const items = ref([
    {
        label: 'Dashboard',
        icon: 'monitor-dashboard',
        url: '/dashboard',
        children: [],
        roles: [
            'admin',
            'ops',
            'ks',
            'guru_kelas',
            'guru_agama',
            'guru_pjok',
            'guru_inggris',
            'siswa']
    },
    {
        label: 'Data Utama',
        icon: 'database',
        url: '#',
        roles: [
            'admin',
            'ops',
            'ks',
            'guru_kelas',
            'guru_agama',
            'guru_pjok',
            'guru_inggris',
            'siswa'
        ],
        children: [
            {
                label: 'Data Operator',
                icon: 'laptop-account',
                url: '/dashboard/operator',
                roles: ['admin']
            },
            {
                label: 'Data Sekolah',
                icon: 'city-variant',
                url: '/dashboard/sekolah',
                roles: ['admin', 'ops']
            },
            {
                label: 'Data Guru',
                icon: 'account-tie',
                url: '/dashboard/guru',
                roles: ['admin', 'ops']
            },
            {
                label: 'Data Rombel',
                icon: 'google-classroom',
                url: '#',
                roles: ['admin', 'ops']
            },
            {
                label: 'Data Siswa',
                icon: 'human-child',
                url: '#',
                roles: ['admin', 'ops']
            },
        ]
    },
    {
        label: 'Data Nilai',
        icon: 'numeric',
        url: '#',
        roles: ['admin', 'ops'],
        children: [
            {
                label: 'Input Nilai',
                icon: 'edit',
                url: '#',
                roles: ['admin', 'ops']
            },
            {
                label: 'Ledger',
                icon: 'spreadsheet',
                url: '#',
                roles: ['admin', 'ops']
            },
        ]
    },
    {
        label: 'Rapor',
        icon: 'report',
        url: '#',
        roles: ['admin', 'ops'],
        children: [
            {
                label: 'Data Periodik',
                icon: 'chart-line',
                url: '#',
                roles: ['admin', 'ops']
            },
            {
                label: 'Cetak',
                icon: 'printer',
                url: '#',
                roles: ['admin', 'ops']
            },
        ]
    },
    {
        label: 'Pengaturan',
        icon: 'settings',
        url: '#',
        roles: ['admin', 'ops'],
        children: [
            {
                label: 'Hak Akses',
                icon: 'account-settings',
                url: '#',
                roles: ['admin']
            },
            {
                label: 'Backup',
                icon: 'harddisk',
                url: '#',
                roles: ['admin', 'ops']
            },
        ]
    },
])

const goto = (url) => {
    router.visit(url)
}

const showItem = (roles) => {
    return roles.includes(page.props.auth.roles[0])
}
</script>

<template>
    <div class="p-2">
        <el-divider>Menu</el-divider>
        <ul>
            <template v-for="(item, i) in items" :key="i">
                <li v-if="item.children && item.children.length < 1 && showItem(item.roles)">
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
                        <template  v-for="(sub, s) in item.children" :key="i+'-'+s">
                            <li v-if="showItem(sub.roles)">
                                <!-- <span>{{ sub.roles }}</span> -->
                                <Link :href="sub.url" :class="{ 'active' : $page.url === sub.url}" class="flex items-center gap-1">
                                    <Icon :icon="`mdi:${sub.icon}`" />
                                    <span>{{ sub.label }}</span>
                                    
                                    <!-- <Icon icon="mdi:chart" -->
                                </Link>
                            </li>
                        </template>
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
    color: #2881b1;
    font-weight: 800;
}
</style>