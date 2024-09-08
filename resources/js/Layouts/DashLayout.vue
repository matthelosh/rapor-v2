<script setup>
import { ref, onMounted, computed } from "vue";
import { router, Head, usePage, Link } from "@inertiajs/vue3";
import { ElContainer, ElHeader, ElAside, ElMain } from "element-plus";
import { Icon } from "@iconify/vue";
// import 'element-plus/es/components/button/style/css'
import SideItem from "@/Layouts/SideMenu.vue";
const contentTrigger = ref(false);
const page = usePage()
const logout = () => {
    router.post(route("logout"));
};

const sideWidth = computed(() => {
    return window.innerWidth <= 414 ? '0%' : '15%'
})

const toggleSide = () => {
    const side = document.querySelector('aside')
    side.style.width = '50%'
    side.classList.toggle("hidden-sm-and-down")
}
onMounted(() => (contentTrigger.value = true));
</script>
<template>
    <div class="common-layout h-screen w-screen">
        <el-container class="h-full w-full">
            <el-aside width="15%" class="max-h-screen hidden-sm-and-down" v-if="page.props.auth.roles[0] !== 'siswa'">
                <div class="side-content h-full bg-slate-100">
                    <el-scrollbar max-height="100vh">
                        <SideItem />
                    </el-scrollbar>
                </div>
            </el-aside>
            <el-container>
                <el-header class="px-8 z-40 bg-white">
                    <div
                        class="content mt-2 w-full flex items-center justify-between h-full p-4 shadow-md rounded-b-md"
                    >
                        <div class="head-title flex items-center gap-2">
                            <Icon icon="mdi:menu" class="text-xl hidden-md-and-up" @click="toggleSide" />
                            <img src="/img/tutwuri.png" class="w-10 hidden-md-and-down" />
                            <slot name="header"></slot>
                        </div>
                        <div class="header-items flex items-center">
                            <Link
                                :href="route('home')"
                                class="flex gap-1 items-center"
                            >
                                <Icon
                                    icon="mdi:home"
                                    class="text-sky-500 text-lg"
                                />
                                <!-- Beranda -->
                            </Link>
                            <el-popconfirm
                                title="Yakin Keluar?"
                                @confirm="logout"
                                class="ml-4"
                            >
                                <template #reference>
                                    <!-- <Link :href="route('logout')" method="post" as="button" class="hover:text-red-600">Logout</Link> -->
                                    <el-button type="danger" circle text>
                                        <Icon
                                            icon="mdi:exit-to-app"
                                            class="text-2xl"
                                        />
                                    </el-button>
                                </template>
                            </el-popconfirm>
                        </div>
                    </div>
                </el-header>
                <el-main>
                    <Transition name="slide-fade" mode="out-in" :duration="500">
                        <div v-if="contentTrigger">
                            <slot />
                        </div>
                    </Transition>
                </el-main>
            </el-container>
        </el-container>
    </div>
</template>

<style>
/* we will explain what these classes do next! */
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}
.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateY(20px);
    opacity: 0;
}

.el-dialog {
    padding: 0!important;
}

.el-dialog__header {
    margin-right: 0!important;
}
</style>
