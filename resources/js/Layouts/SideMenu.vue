<script setup>
import { ref, computed } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { avatar } from '@/helpers/Gambar.js'

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
            'kepala_sekolah',
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
            'kepala_sekolah',
            'guru_kelas',
            'guru_agama',
            'guru_pjok',
            'guru_inggris',
            'siswa'
        ],
        children: [
            {
                label: 'Operator',
                icon: 'laptop-account',
                url: '/dashboard/operator',
                roles: ['admin', 'kepala_sekolah']
            },
            {
                label: 'Sekolah',
                icon: 'city-variant',
                url: '/dashboard/sekolah',
                roles: ['admin', 'ops', 'kepala_sekolah',]
            },
            {
                label: 'Guru',
                icon: 'account-tie',
                url: '/dashboard/guru',
                roles: ['admin', 'ops', 'kepala_sekolah', 'guru_kelas','guru_agama','guru_pjok','guru_inggris']
            },
            {
                label: 'Siswa',
                icon: 'human-child',
                url: '/dashboard/siswa',
                roles: ['admin', 'ops', 'guru_kelas']
            },
            {
                label: 'Rombel',
                icon: 'google-classroom',
                url: '/dashboard/rombel',
                roles: ['admin', 'ops', 'guru_kelas']
            },
            {
                label: 'Pembelajaran',
                icon: 'bookshelf',
                url: '/dashboard/pembelajaran',
                roles: ['admin', 'ops']
            },
        ]
    },
    {
        label: 'Data Nilai',
        icon: 'numeric',
        url: '#',
        roles: ['guru_kelas', 'guru_agama','guru_pjok','guru_inggris', 'ops'],
        children: [
            {
                label: 'Input Nilai',
                icon: 'edit',
                url: '/dashboard/nilai',
                roles: ['guru_kelas', 'guru_agama','guru_pjok','guru_inggris']
            },
            {
                label: 'Ledger',
                icon: 'spreadsheet',
                url: '/dashboard/ledger',
                roles: [ 'guru_kelas', 'guru_agama','guru_pjok','guru_inggris']
            },
        ]
    },
    {
        label: 'Rapor',
        icon: 'report',
        url: '#',
        roles: ['guru_kelas', 'ops'],
        children: [
            {
                label: 'Data Periodik',
                icon: 'chart-line',
                url: '/dashboard/rapor/periodik',
                roles: ['guru_kelas']
            },
            {
                label: 'Cetak',
                icon: 'printer',
                url: '/dashboard/rapor/cetak',
                roles: ['guru_kelas', 'kepala_sekolah']
            },
            {
                label: 'Tanggal Rapor',
                icon: 'calendar',
                url: '/dashboard/rapor/tanggal',
                roles: ['ops']
            },
            {
                label: 'Arsip',
                icon: 'archive',
                url: '/dashboard/rapor/arsip',
                roles: ['ops']
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
                url: '/dashboard/roles',
                roles: ['admin']
            },
            {
                label: 'Backup',
                icon: 'harddisk',
                url: '/dashboard/backup',
                roles: ['admin']
            },
        ]
    },
])

const goto = (url) => {
    router.visit(url)
}

// const avatar = () => {
//     return page.props.auth.roles.includes('admin') ? '/img/user_l.png' : (page.props.auth.user.userable.jk == 'Laki-laki' ? '/img/user_l.png' : (page.props.auth.user.agama == 'Islam' ? '/img/user_p_is.png': '/img/user_p.png'))
// }
const toggleChild = (e) => {
    const li = e.target.closest('li')
    const child = li.querySelector("ul.child")
    child.classList.toggle('hidden')
}

const showItem = (roles) => {
    return roles.includes(page.props.auth.roles[0])
}
</script>

<template>
    <div class="p-0">
        <div class="avatar relative">
            <img :src="avatar()" class="rounded" />
            <h3 class="absolute bottom-0 bg-sky-800 text-center text-white font-black tracking-wide w-full py-3 px-2 bg-opacity-90">{{ page.props.auth.user.userable ? page.props.auth.user.userable.nama : page.props.auth.user.name}}</h3>
        </div>
        <div class="menu-item py-2 px-4">
            <el-divider>Menu</el-divider>
            <el-scrollbar height="500">
                <ul>
                    <template v-for="(item, i) in items" :key="i">
                        <li v-if="item.children && item.children.length < 1 && showItem(item.roles)">
                            <Link :href="item.url" :class="{ 'active' : $page.url === item.url}" class="flex items-center gap-1">
                                <Icon :icon="`mdi:${item.icon}`" />
                                <span>{{ item.label }}</span>
                            </Link>
                        </li>
                        <li v-else class="group" >
                            <a :href="item.url" @click="toggleChild" class="parent flex justify-between items-center gap-1 text-slate-600 " >
                                <span class="flex items-center gap-1">
                                    <Icon :icon="`mdi:${item.icon}`" />
                                    <span>{{ item.label }}</span>
                                </span>
                                <Icon icon="mdi:chevron-up" />
                            </a>
                            <ul class="pl-4 pt-1 child">
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
            </el-scrollbar>
        </div>
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

ul.child {
    transition: all .35s ease-in-out .5s;
}
</style>