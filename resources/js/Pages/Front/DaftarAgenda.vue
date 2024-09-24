<script setup>
import { ref, computed, defineAsyncComponent, onMounted } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import Header from '@/Layouts/Front/Header.vue';
import axios from 'axios';
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Footer.vue'))

const page = usePage();
const search = ref('')
defineProps({
    agenda: Object,
    appName: String
});


const params = computed(() => route().params)
const captcha = ref(null)

const getCaptcha = async() => {
    axios.get('/captcha/new').then( res => {
        captcha.value = URL.createObjectURL(res.data);
    })
}

function hexToBase64(str) {
    return btoa(String.fromCharCode.apply(null, str.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" ")));
}

onMounted(() => {
    getCaptcha()
})
</script>

<template>
    <Head title="Selamat Datang" />
    <div class="common-layout">
        <div class="wrapper">
            <Header :appName="appName" />
            <el-main>
                {{ agenda }}
                <img :src="captcha" alt="Captcha">
            </el-main>
            <Footer />
        </div>
    </div>
</template>

