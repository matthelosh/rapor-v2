<script setup>
import { router, Head, usePage, Link } from '@inertiajs/vue3'
import {ElContainer, ElHeader, ElAside,ElMain } from 'element-plus'
// import 'element-plus/es/components/button/style/css'
import SideItem from './SideMenu.vue'
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
                <Link :href="route('logout')" method="post" as="button" class="hover:text-red-600">Logout</Link>
            </div>
        </el-header>
        <el-main >
          <el-scrollbar >
            <slot />
          </el-scrollbar>
        </el-main>
      </el-container>
    </el-container>
</div>
</template>