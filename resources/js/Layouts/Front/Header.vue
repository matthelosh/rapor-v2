<script setup>
import { ref, computed } from 'vue'
import { Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import menus from '@/helpers/frontMenu.json'

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    appName: String,
});

const showDrawer = ref(false)
</script>

<template>
    <header class="navbar z-40">
        <div class="navbar-container">
            <h3 class="uppercase">
                <Link href="/" class="flex items-center gap-1">
                    <img src="/img/tutwuri.png" alt="Logo" class="h-[28px]">
                    {{ appName }}
                </Link>
            </h3>
            <nav class="gap-4 uppercase hidden md:flex">
                <template v-for="(menu, m) in menus" :key="m">
                    <Link :href="menu.url" v-if="menu.children.length < 1">
                        {{ menu.label }}
                    </Link>
                    <el-popover v-if="menu.children.length > 0" trigger="click" class="hover:cursor-pointer" width="160">
                        <template #reference>
                            <a href="#">
                                {{ menu.label }}
                            </a>
                        </template>
                        <ul >
                            <li v-for="sub in menu.children" class="px-2 py-3 hover:bg-sky-100 uppercase">
                                <Link :href="sub.url">{{ sub.label }}</Link>
                            </li>
                        </ul>
                    </el-popover>
                </template>
                <Link href="/login" class="flex items-center">
                    <Icon icon="mdi:application-import"  />
                </Link>
            </nav>
            <Icon icon="mdi:dots-vertical" class="text-2xl inline md:hidden" @click="showDrawer = !showDrawer" />
        </div>
    </header>
    <el-drawer v-model="showDrawer" size="60%" :withHeader="false">
        <nav class="gap-2 flex-col flex">
            <template v-for="(menu, m) in menus" :key="m">
                <Link :href="menu.url">
                    {{ menu.label }}
                </Link>
            </template>
            <Link href="/login" class="flex items-center">
                <Icon icon="mdi:application-import"  />
            </Link>  
        </nav>
    </el-drawer>
</template>

<style>
header {
    height: 60px;
    box-shadow: 0 5px 10px rgba(0,0,0,0.2);
    position: sticky;
    top: 0;
}
.navbar .navbar-container {
    color: #efefef;
    height: 100%;   
    background: rgb(27, 110, 226);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}

@media only screen and (min-width: 736px) {
    .navbar .navbar-container {
        padding: 0 15.5%;
    }
}
</style>