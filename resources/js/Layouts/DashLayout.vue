<script setup>
import { ref, onMounted, computed, onBeforeMount } from "vue";
import { router, Head, usePage, Link } from "@inertiajs/vue3";
import { ElContainer, ElHeader, ElAside, ElMain } from "element-plus";
import { Icon } from "@iconify/vue";
// import 'element-plus/es/components/button/style/css'
import SideItem from "@/Layouts/SideMenu.vue";
const contentTrigger = ref(true);
const page = usePage();
const logout = () => {
    router.post(route("logout"));
};

const sideWidth = computed(() => {
    return window.innerWidth <= 414 ? "0%" : "15%";
});

const toggleSide = () => {
    const side = document.querySelector("aside");
    side.style.width = "50%";
    side.classList.toggle("hidden-sm-and-down");
};

// const selectedSemester = ref('')
// const onSemesterChanged = (e) => {
//     alert(e)
// }

onMounted(() => {
    contentTrigger.value = true;

});
onBeforeMount(() => {
    // selectedSemester.value = page.props.periode.semester.kode
})
</script>
<template>
    <div
        class="common-layout h-screen w-screen bg-gradient-to-br from-sky-400 to-indigo-500 relative"
    >
        <div class="ornamen absolute top-0 right-0 bottom-0 left-0">
            <img
                src="/img/meja_kerja.svg"
                alt="Meja Kerja"
                class="absolute bottom-8 right-64 w-60"
            />
            <img
                src="/img/asn.svg"
                alt="Meja Kerja"
                class="absolute bottom-8 right-8 h-72"
            />
            <img
                src="/img/buku.svg"
                alt="Meja Kerja"
                class="absolute left-32 top-64 h-48"
            />
        </div>
        <el-container class="h-full w-full z-20 relative">
            <el-aside
                width="15%"
                class="max-h-screen hidden-sm-and-down"
                v-if="page.props.auth.roles[0] !== 'siswa'"
            >
                <div
                    class="side-content h-full bg-slate-100 bg-opacity-70 backdrop-blur-md"
                >
                    <el-scrollbar max-height="100vh">
                        <SideItem />
                    </el-scrollbar>
                </div>
            </el-aside>
            <div class="w-full">
                <el-header class="px-8 z-40">
                    <div
                        class="content bg-white bg-opacity-60 backdrop-blur-md mt-2 w-full flex items-center justify-between h-full p-4 shadow-md rounded-b-md"
                    >
                        <div class="head-title flex items-center gap-2">
                            <span>
                                <Icon
                                    v-if="page.props.auth.roles[0] !== 'siswa'"
                                    icon="mdi:menu"
                                    class="text-xl hidden-md-and-up"
                                    @click="toggleSide"
                                />
                                <Link v-else :href="route('dashboard')">
                                    <Icon icon="mdi:home" />
                                </Link>
                            </span>
                            <img
                                src="/img/logo_pkg.png"
                                class="w-10 hidden-md-and-down"
                            />
                            <slot name="header"></slot>
                        </div>
                        <div class="header-items flex items-center justify-end flex-grow gap-2">
                            <p class="px-2 bg-sky-700 rounded text-white">Semester {{page.props.periode.semester.label}} | {{page.props.periode.tapel.label}}</p>
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
                <el-main class="h-[90vh]">
                    <Transition name="slide-fade" mode="out-in" :duration="500">
                        <div v-if="contentTrigger">
                            <slot />
                        </div>
                    </Transition>
                </el-main>
            </div>
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
    padding: 0 !important;
}

.el-dialog__header {
    margin-right: 0 !important;
}

.el-card {
    background-color: #ffffffab;
    backdrop-filter: blur(8px);
}

.el-table {
    border-radius: 5px;
    /* overflow: hidden; */
}
</style>
