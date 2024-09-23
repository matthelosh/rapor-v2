<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { Calendar } from 'v-calendar';
import 'v-calendar/style.css';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
dayjs.locale('id')
import { ElNotification } from 'element-plus';

const props = defineProps({ agendas: Array, orgs: Array })
const mode = ref('calendar')

const DashLayout = defineAsyncComponent(() => import('@/Layouts/DashLayout.vue'))

const loading = ref(false)
const layout = computed(() => {
    const width = window.innerWidth
    let cols = width < 414 ? 1 : (width <= 768 ? 2 : (width <= 1366 ? 3 : (width <= 1920 ? 4 : 6)))
    let rows = width < 414 ? 12 : (width <= 768 ? 6 : (width <= 1366 ? 4 : (width <= 1920 ? 3 : 2)))
    return {
        cols, rows
    }
})

const agenda = ref({})
const selectedAgenda = ref(null)
const formAgenda = ref(false)

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

const closeForm = () => {
    formAgenda.value = false
    selectedAgenda.value = null
    agenda.value = {}
}
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

    formAgenda.value = true
}

const simpan = async() => {
    // loading.value = true
    router.post(route('dashboard.agenda.store'), {data: agenda.value}, {
        onStart: () => {
            loading.value = true
        },
        onSuccess: (page) => {
            ElNotification({ title: 'Info', message: page.props.flash.message, type: 'success'})
            router.reload({only: ['agendas']})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({ title: "Error", message: errs[k], type: 'error'})
                }, 500)
            })
        },
        onFinish: () => loading.value = false
    })
}
</script>

<template>
<Head title="Agenda" />
<DashLayout>
    <template #header>Agenda Kegiatan</template>
    <el-card>
        <template #header>
            <div class="flex items-center justify-between">
                <h3 class="flex items-center gap-1">
                    <Icon icon="mdi:calendar-check" class="text-lg" />
                    <span class="font-bold">Kalender Pendidikan</span>
                </h3>
                <div class="toolbar-items flex items-center gap-2">
                    <el-button text type="danger" v-if="mode !== 'timeline' " @click="mode = 'timeline'">
                        <Icon icon="mdi:timeline" />
                    </el-button>
                    <el-button text type="danger" v-if="mode !== 'calendar' " @click="mode = 'calendar'">
                        <Icon icon="mdi:calendar" />
                    </el-button>
                </div>
            </div>
        </template>
        <template #default>
            <el-scrollbar max-height="80vh">
                <div class="agenda" v-if="mode == 'calendar'">
                    <Calendar  min-date="2024-07-01" max-date="2025-06-30" :rows="layout.rows" :columns="layout.cols" expanded :attributes="attributes" @dayclick="onDayClicked" />
                    <el-card class="mt-4">
                    <h3 class="mb-4">Detail Agenda</h3>
                        <el-table :data="props.agendas">
                            <el-table-column label="#" type="index"></el-table-column>
                            <el-table-column label="Nama" prop="nama"></el-table-column>
                            <el-table-column label="Tipe" prop="tipe"></el-table-column>
                            <el-table-column label="Pelaksana" prop="pelaksana"></el-table-column>
                            <el-table-column label="Peserta">
                                <template #default="{row}">
                                    {{ row.pesertas?.length }}
                                </template>
                            </el-table-column>
                            <el-table-column label="Opsi">
                                <template #default="{row}">
                                    <el-button-group size="small">
                                        <el-button>Daftar Hadir</el-button>
                                        <el-button>Kartu Peserta</el-button>
                                        <el-button>Sertifikat</el-button>
                                    </el-button-group>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-card>
                </div>
                <el-timeline v-if="mode == 'timeline'">
                    <el-timeline-item
                        v-for="(agenda, a) in props.agendas" :key="a"
                        :timestamp="dayjs(agenda.mulai).format('dddd, DD MMM YYYY')"
                        :color="agenda.warna"
                    >
                        {{ agenda.nama }}
                    </el-timeline-item>
                </el-timeline>
            </el-scrollbar>
        </template>
    </el-card>
</DashLayout>
<el-dialog v-model="formAgenda" draggable :show-close="false" style="padding: 0;">
    <template #header style="margin:0; padding:0; background: pink;">
        <div class="flex items-center justify-between">
            <h3><span>{{ selectedAgenda === null ? 'Buat' : 'Edit' }}</span> Agenda {{ selectedAgenda?.nama }}</h3>
            <el-button circle type="danger" @click="closeForm">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </template>
    <template #default>
        <el-form v-model="agenda" label-width="150" v-loading="loading">
            <el-form-item label="Nama">
                <el-input v-model="agenda.nama" placeholder="Nama Kegiatan"></el-input>
            </el-form-item>
            <el-form-item label="Tipe Agenda">
                <el-select v-model="agenda.tipe" placeholder="Tipe Kegiatan">
                    <el-option v-for="tipe in ['libur', 'kegiatan']" :value="tipe" :label="tipe[0].toUpperCase()+tipe.substring(1)"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="Pelaksana">
                <el-select v-model="agenda.pelaksana" placeholder="Pelaksana Kegiatan">
                    <el-option v-for="org in props.orgs" :value="org.nama"></el-option>
                </el-select>
            </el-form-item>
            <div class="flex items-center gap-2">
                <el-form-item label="Mulai">
                    <el-date-picker v-model="agenda.mulai" value-format="YYYY-MM-DD" placeholder="Mulai Kegiatan"></el-date-picker>
                </el-form-item>
                <el-form-item label="Selesai">
                    <el-date-picker v-model="agenda.selesai" value-format="YYYY-MM-DD" placeholder="Selesai Kegiatan"></el-date-picker>
                </el-form-item>
            </div>
            <el-form-item label="Deskripsi">
                <el-input type="textarea" v-model="agenda.deskripsi" placeholder="Deskripsi Kegiatan"></el-input>
            </el-form-item>
        </el-form>
    </template>
    <template #footer>
        <div class="flex items-center justify-end">
            <el-button type="primary" @click="simpan" :loading="loading">Simpan</el-button>
        </div>
    </template>
</el-dialog>
</template>