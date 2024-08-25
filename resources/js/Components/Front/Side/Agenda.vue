<script setup>
import { computed } from 'vue';
import { Calendar } from 'v-calendar';
import 'v-calendar/dist/style.css'

const props = defineProps({agendas: Array})

const attributes = computed(() => {
    let attrs = []
    props.agendas.forEach((item, index) => {
        attrs.push({
            key: item.id, 
            highlight: item.warna, 
            dates: {start: new Date(item.mulai), end: new Date(item.selesai)},
            startDate: new Date(item.mulai),
            endDate: new Date(item.selesai),
            popover: {
                label: `${item.nama}`,
                visibility: 'hover'
            },
            index: index,
            description: item.deskripsi,
            customData: item
        })
    })

    return attrs
})
</script>

<template>
<Calendar expanded :attributes="attributes" />
</template>