<script setup>
import { ref, computed } from 'vue'
import { Link } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

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
    <header class="navbar">
        <div class="navbar-container">
            <h3 class="uppercase">
                <Link href="/" class="flex items-center gap-1">
                    <img src="/img/tutwuri.png" alt="Logo" class="h-[28px]">
                    {{ appName }}
                </Link>
            </h3>
            <nav class="gap-4 uppercase hidden md:flex">
                <Link href="/">
                    Beranda
                </Link>
                <Link href="#">
                    Profil
                </Link>
                <Link href="#">
                    Berita
                </Link>
                <Link href="/login" class="flex items-center">
                    <Icon icon="mdi:application-import"  />
                </Link>
            </nav>
            <Icon icon="mdi:dots-vertical" class="text-2xl" @click="showDrawer = !showDrawer" />
        </div>
    </header>
    <el-drawer v-model="showDrawer" size="60%" :withHeader="false">
        <nav class="gap-2 flex-col flex">
                <Link href="/">
                    Beranda
                </Link>
                <Link href="#">
                    Profil
                </Link>
                <Link href="#">
                    Berita
                </Link>
                <Link href="/login" class="flex items-center gap-1">
                    <Icon icon="mdi:application-import"  />
                    Login
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