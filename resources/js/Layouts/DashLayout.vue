<script setup>
import { ref, onMounted } from 'vue';
import { router, Head, usePage, Link } from '@inertiajs/vue3'
import {ElContainer, ElHeader, ElAside,ElMain } from 'element-plus'
import { Icon } from '@iconify/vue';
// import 'element-plus/es/components/button/style/css'
import SideItem from './SideMenu.vue'

const contentTrigger = ref(false)

const logout = () => {
  router.post(route('logout'))
}

onMounted(() => contentTrigger.value = true)
</script>
<template>
<div class="common-layout h-screen w-screen ">
    <el-container class="h-full w-full">
      <el-aside width="15%" class=" h-full">
        <div class="side-content h-full  bg-slate-100">
            <SideItem />
        </div>
      </el-aside>
      <el-container>
        <el-header class="bg-slate-200 flex items-center justify-between shadow">
            <div class="head-title flex items-center gap-2">
                <img src="/img/tutwuri.png" class="w-10" />
                <slot name="header"></slot>
            </div>
            <div class="header-items">
              <el-popconfirm title="Yakin Keluar?" @confirm="logout">
                <template #reference>
                  <!-- <Link :href="route('logout')" method="post" as="button" class="hover:text-red-600">Logout</Link> -->
                   <el-button type="danger" circle text>
                    <Icon icon="mdi:exit-to-app" class="text-2xl" />
                   </el-button>
              </template>
              </el-popconfirm>
            </div>
        </el-header>
        <el-main >
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
</style>