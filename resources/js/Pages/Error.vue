<script setup>
import { computed } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import DashLayout from '@/Layouts/DashLayout.vue'

const page = usePage()

const props = defineProps({ status: Number })

const title = computed(() => {
  return {
    503: '503: Service Unavailable',
    500: '500: Server Error',
    404: '404: Page Not Found',
    403: '403: Forbidden',
  }[props.status]
})

const description = computed(() => {
  return {
    503: 'Sorry, we are doing some maintenance. Please check back soon.',
    500: 'Whoops, something went wrong on our servers.',
    404: 'Sorry, the page you are looking for could not be found.',
    403: 'Maaf, Anda tidak memiliki hak mengakses laman ini',
  }[props.status]
})
</script>

<template>
  <Head :title="title" />
  <DashLayout>
    <template #header>
      {{ title }}
    </template>
    <el-alert type="error" class="page flex items-center justify-center h-[83vh] bg-pink-100">
        <div class="text-center">
            <Icon icon="mdi-hand" class="text-2xl mx-auto" />
            <h1 class="font-black text-xl uppercase">{{ title }}</h1>
            <div>{{ description }}</div>
        </div>
    </el-alert>
  </DashLayout>
</template>