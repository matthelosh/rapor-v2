<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { capitalize } from '@/helpers/String'
import { Calendar } from 'v-calendar';
import 'v-calendar/style.css';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
dayjs.locale('id')

import Header from '@/Layouts/Front/Header.vue';
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Footer.vue'))

const props = defineProps({agendas: Array, appName: String})
const mode = ref('calendar')
const layout = computed(() => {
    const width = window.innerWidth
    let cols = width >=1920 ? 4: (width >= 1366 ? 3 : 1)
    let rows = width >= 1920 ? 3 : (width >= 1366 ? 4 : 1)
    return {
        cols, rows
    }
})

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

const dialog = ref(false)
const agenda = ref({})
const selectedAgenda = ref({})
const onDayClicked = async(calendar, $event) => {
    let items = []
    if ( calendar.attributeCells.length > 0 ) {
        const cells = calendar.attributeCells
        // console.log(cells)
        await cells.forEach(cell => items.push({index: cell.data.index, label: cell.data.description}))
        selectedAgenda.value = props.agendas.find((element) => element.id == cells[0].data.key)
        // selectedAgenda.is_libur = selectedAgenda.is_libur == 1 ? true : false
        agenda.value = selectedAgenda.value

    } else {
        agenda.value.mulai = calendar.id
        agenda.value.selesai = calendar.id
    }

    dialog.value = true
}
</script>

<template>
    <Head title="Agenda Kegiatan" />
    <div class="common-layout">
        <div class="wrapper">
            <Header :appName="props.appName" />
            <el-main>
                <el-row justify="center">
                    <el-col :span="17" :xs="24">
                        <h1 class="text-4xl font-black uppercase text-center mb-4 text-sky-800">Agenda Kegiatan PKG Kecamatan Wagir</h1>
                        <Calendar v-if="mode == 'calendar'" min-date="2024-07-01" max-date="2025-06-30" :rows="layout.rows" :columns="layout.cols" expanded :attributes="attributes" @dayclick="onDayClicked" />
                        <div class="my-4">
                            <h3>Keterangan:</h3>
                            <el-table :data="agendas" max-height="400">
                                <el-table-column label="#" type="index"></el-table-column>
                                <el-table-column label="Mulai" width="150">
                                    <template #default="scope">
                                        {{ dayjs(scope.row.mulai).locale('id').format('DD MMMM YYYY') }}
                                    </template>
                                </el-table-column>
                                <el-table-column label="Selesai" width="150">
                                    <template #default="scope">
                                        {{ dayjs(scope.row.selesai).locale('id').format('DD MMMM YYYY') }}
                                    </template>
                                </el-table-column>
                                <el-table-column label="Nama" prop="nama" width="300"></el-table-column>
                                <el-table-column label="Deskripsi" prop="deskripsi"></el-table-column>
                            </el-table>
                        </div>
                    </el-col>
                </el-row>
            </el-main>
            <Footer />
        </div>
    </div>
    <el-dialog v-model="dialog">
        {{ agenda }}
    </el-dialog>
</template>

<style>
.el-dialog {
    padding: 0;
}

.el-dialog__header {
    margin:0;
}
</style>