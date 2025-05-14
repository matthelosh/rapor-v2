<script setup>
import { ref, computed, onMounted, onBeforeMount } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { ElNotification } from "element-plus";
import axios from "axios";

const props = defineProps({ selectedAsesmen: Object, show: Boolean });
const emit = defineEmits(["close", "update-data"]);
const page = usePage();
const shown = ref(false);
const processes = ref([]);
const colors = [
    { color: "#f56c6c", percentage: 20 },
    { color: "#e6a23c", percentage: 40 },
    { color: "#5cb87a", percentage: 60 },
    { color: "#1989fa", percentage: 80 },
    { color: "#6fff63", percentage: 100 },
];
const reloadData = async () => {
    axios
        .post(
            route("dashboard.asesmen.monitor.reload", {
                _query: { asesmenId: props.selectedAsesmen.id },
            }),
        )
        .then((res) => {
            processes.value = res.data.asesmen.proses;
        });
};

const percentage = (total) => {
    return Math.round((total / props.selectedAsesmen.soals.length) * 100);
};

function handleEvent(notif) {
    ElNotification({ title: "Info", message: notif.message, type: "info" });
    reloadData();
}

onBeforeMount(() => {
    processes.value = props.selectedAsesmen.proses;
    shown.value = props.show;
    window.Echo.private(`asesmen.${page.props.auth.user.id}`)
        .listen("SiswaMulaiAsesmen", handleEvent)
        .listen("SiswaMenjawab", handleEvent)
        .listen("SiswaLoggedOut", handleEvent);
});
</script>

<template>
    <el-dialog
        v-model="shown"
        fullscreen
        style="min-height: 100vh !important"
        :show-close="false"
    >
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Monitoring Asesmen {{ props.selectedAsesmen.nama }}</h3>
                <div class="flex items-center gap-1">
                    <el-button @click="emit('update-data')">
                        <Icon icon="mdi:reload-alert" class="text-xl" />
                    </el-button>
                    <el-button @click="emit('close')" type="danger" circle>
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <!-- {{ processes }} -->
        <table class="w-full border border-sky-200">
            <thead>
                <tr>
                    <th class="border border-sky-500 p-2 bg-sky-700 text-white">
                        No
                    </th>
                    <th class="border border-sky-500 p-2 bg-sky-700 text-white">
                        Kode Proses
                    </th>
                    <th class="border border-sky-500 p-2 bg-sky-700 text-white">
                        Siswa
                    </th>
                    <th class="border border-sky-500 p-2 bg-sky-700 text-white">
                        Status
                    </th>
                    <th class="border border-sky-500 p-2 bg-sky-700 text-white">
                        Jawaban
                    </th>
                    <th class="border border-sky-500 p-2 bg-sky-700 text-white">
                        Progress
                    </th>
                    <th class="border border-sky-500 p-2 bg-sky-700 text-white">
                        Skor
                    </th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(proses, p) in processes" :key="p">
                    <tr class="odd:bg-sky-50">
                        <td
                            class="border-x border-sky-200 p-2 text-center w-[50px]"
                        >
                            {{ p + 1 }}
                        </td>
                        <td
                            class="border-x border-sky-200 p-2 text-center w-[60px]"
                        >
                            {{ proses.id }}
                        </td>
                        <td class="border-x border-sky-200 p-2">
                            <div class="flex items-center gap-2">
                                {{ proses.siswa?.nama }}
                            </div>
                        </td>
                        <td
                            class="border-x border-sky-200 p-2 text-center w-[100px]"
                        >
                            <div class="flex items-center gap-2 justify-center">
                                <div
                                    class="indicator w-[10px] h-[10px] rounded-full"
                                    :class="
                                        proses.siswa.user[0].is_online
                                            ? 'bg-green-500 animate-pulse'
                                            : 'bg-slate-300'
                                    "
                                ></div>
                                <p
                                    class="text-sky-500"
                                    v-if="proses.siswa.user[0].is_online"
                                >
                                    Online
                                </p>
                                <p
                                    class="text-slate-400"
                                    v-if="!proses.siswa.user[0].is_online"
                                >
                                    Offline
                                </p>
                            </div>
                        </td>
                        <td
                            class="border-x border-sky-200 p-2 text-center w-[100px]"
                        >
                            {{ proses.jawabans?.length }}
                        </td>
                        <td class="border-x border-sky-200 p-2 text-center">
                            <el-progress
                                type="circle"
                                :width="80"
                                :stroke-width="10"
                                :percentage="percentage(proses.jawabans.length)"
                                :color="colors"
                                :type="'success'"
                            >
                                <template #default="{ percentage }">
                                    <div class="percentage-value">
                                        {{ percentage }}%
                                    </div>
                                    <div class="percentage-label">
                                        {{ proses.status }}
                                    </div>
                                </template>
                            </el-progress>
                        </td>
                        <td
                            class="border-x border-sky-200 p-2 text-center w-[150px]"
                        >
                            {{
                                proses.jawabans.length > 0
                                    ? proses.jawabans.reduce(
                                          (a, c) => (a += c.is_benar),
                                          0,
                                      )
                                    : 0
                            }}
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </el-dialog>
</template>
