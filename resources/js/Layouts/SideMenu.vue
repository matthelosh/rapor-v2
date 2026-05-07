<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { router, Link, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
// import { avatar } from "@/helpers/Gambar.js";
import axios from "axios";
import items from "@/helpers/dashMenu.json";
const userDetail = ref({});
const page = usePage();
const goto = (url) => {
    router.visit(url);
};

const avatar = () => {
    return page.props.auth.roles.includes("admin")
        ? "/img/user_l.png"
        : userDetail.value.jk == "Laki-laki"
          ? "/img/user_l.png"
          : userDetail.value.agama == "Islam"
            ? "/img/user_p_is.png"
            : "/img/user_p.png";
};
// das
const activeMenu = ref(null);

const toggleChild = (itemId) => {
    activeMenu.value = activeMenu.value === itemId ? null : itemId;
    // const li = e.target.closest("li");
    // const child = li.querySelector("ul.child");
    // child.classList.toggle("hidden");
};

const showItem = (roles) => {
    return roles.includes(page.props.auth.roles[0]);
};

const isChildRouteIsActive = (children) => {
    if (!children) return false;
    return children.some((child) => page.url === child.url);
};

watch(
    () => page.url,
    () => {
        items.forEach((item, index) => {
            if (
                item.children &&
                item.children.length > 0 &&
                isChildRouteIsActive(item.children)
            ) {
                activeMenu.value = index;
            }
        });
    },
    { immediate: true },
);

onMounted(async () => {
    items.forEach((item, index) => {
        if (
            item.children &&
            item.children.length > 0 &&
            isChildRouteIsActive(item.children)
        ) {
            activeMenu.value = index;
        }
    });

    axios
        .get(page.props.appUrl + "/userdetail")
        .then((res) => (userDetail.value = res.data.userdetail))
        .catch((err) => console.log(err));
});
</script>

<template>
    <div class="p-0">
        <div class="avatar relative py-4">
            <img
                :src="avatar(page.props.auth.user)"
                class="rounded-full shadow-lg object-cover w-32 mx-auto clip-path"
            />
            <h3
                class="text-center bg-sky-600 p-2 text-white font-bold text-slate-700 transition-all duration-300"
            >
                <Link :href="route('profile.edit')">
                    {{
                        userDetail ? userDetail.nama : page.props.auth.user.name
                    }}
                </Link>
            </h3>
        </div>
        <div class="menu-item py-1 px-4">
            <el-divider>Menu</el-divider>
            <el-scrollbar height="500">
                <ul>
                    <template v-for="(item, i) in items" :key="i">
                        <li
                            v-if="
                                item.children &&
                                item.children.length < 1 &&
                                showItem(item.roles)
                            "
                            class="py-2"
                        >
                            <Link
                                :href="item.url"
                                :class="{ active: $page.url === item.url }"
                                class="flex items-center gap-1"
                            >
                                <Icon :icon="`mdi:${item.icon}`" />
                                <span>{{ item.label }}</span>
                            </Link>
                        </li>
                        <li
                            v-if="
                                item.children.length > 0 && showItem(item.roles)
                            "
                            class="group py-2"
                        >
                            <a
                                :href="item.url"
                                @click.stop="toggleChild(i)"
                                class="parent flex justify-between items-center gap-1 text-slate-600"
                            >
                                <span class="flex items-center gap-1">
                                    <Icon :icon="`mdi:${item.icon}`" />
                                    <span>{{ item.label }}</span>
                                </span>
                                <Icon icon="mdi:chevron-up" />
                            </a>
                            <ul
                                class="pl-4 pt-1 child"
                                :class="{ hidden: activeMenu !== i }"
                            >
                                <template
                                    v-for="(sub, s) in item.children"
                                    :key="i + '-' + s"
                                >
                                    <li v-if="showItem(sub.roles)" class="py-1">
                                        <!-- <span>{{ sub.roles }}</span> -->
                                        <Link
                                            :href="sub.url"
                                            :class="{
                                                active: $page.url === sub.url,
                                            }"
                                            class="flex items-center gap-1"
                                        >
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
    height: 30px !important;
    line-height: 20px !important;
}
a.active {
    color: #2881b1;
    font-weight: 800;
}

ul.child {
    transition: all 0.35s ease-in-out 0.5s;
}
</style>
