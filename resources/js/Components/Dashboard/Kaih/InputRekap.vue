<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { router, usePage } from "@inertiajs/vue3";
const props = defineProps({ rombel: Object, siswa: Object, bulan: String });
import dayjs from "dayjs";
import "dayjs/locale/id";

const page = usePage();
const loading = ref(false);
const kegiatans = ref({
    "Bangun Pagi": [],
    Beribadah: [],
    Berolahraga: [],
    "Makan Sehat dan Bergizi": [],
    "Gemar Belajar": [],
    Bermasyarakat: [],
    "Tidur Cepat": [],
});

const tanggals = computed(() => {
    const dates = [];
    const startDate = dayjs(props.bulan + "-01");
    const daysInMonth = startDate.daysInMonth();
    for (let i = 0; i < daysInMonth; i++) {
        const currentDate = startDate.add(i, "day").format("YYYY-MM-DD");
        dates.push({
            tanggal: currentDate,
        });
    }
    return dates;
});

const parseDone = () => {
    props.siswa.kaihs.forEach((item) => {
        kegiatans.value[item.kebiasaan].push(
            dayjs(item.waktu).locale("id_ID").format("YYYY-MM-DD"),
        );
    });
};

const simpan = async () => {
    const data = {
        rombelId: props.rombel.kode,
        siswaId: props.siswa.nisn,
        keterangan: "Input Manual",
        kegiatans: kegiatans.value,
    };
    // console.log(kegiatans.value);
    router.post(route("dashboard.kaih.rekap.input"), data, {
        onStart: () => (loading.value = true),
        onSuccess: () => {
            ElNotification({
                title: "info",
                message: page.props.flash.message,
                type: "success",
            });
        },
    });
};

onBeforeMount(() => {
    parseDone();
});
</script>
<template>
    <!-- <div>{{ siswa.kaihs }}</div> -->
    <el-collapse accordion>
        <template v-for="label in Object.keys(kegiatans)" :key="`keg-${label}`">
            <el-collapse-item :title="label">
                <!-- <div class="flex flex-wrap justify-center"> -->
                <el-checkbox-group
                    v-model="kegiatans[label]"
                    class="flex flex-wrap gap-1"
                >
                    <template v-for="(item, t) in tanggals" :key="`tgl-${t}`">
                        <el-checkbox
                            :label="
                                dayjs(item.tanggal).locale('id_ID').format('DD')
                            "
                            :value="item.tanggal"
                            border
                            style="
                                width: 60px !important;
                                margin: 5px !important;
                            "
                        >
                        </el-checkbox>
                    </template>
                </el-checkbox-group>
                <!-- </div> -->
            </el-collapse-item>
        </template>
    </el-collapse>
    <div class="flex items-center justify-end mt-4">
        <el-button @click="simpan">Simpan</el-button>
    </div>
</template>
