<script setup>
import { ref, computed, onBeforeMount, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { ElNotification } from "element-plus";
import axios from "axios";

const page = usePage();
const props = defineProps({ open: Boolean, selectedRombel: Object });
const emit = defineEmits(["close"]);
const show = computed(() => props.open);
const loading = ref(false);
const logoUrl = ref("/img/tutwuri.png");
const fileLogo = ref(null);
const gurus = ref([]);
const rombel = ref({
    tapel: "",
    pararel: "",
    kode: "",
    label: "",
    fase: "",
    tingkat: "",
    sekolah_id: "",
    guru_id: "",
    is_active: "",
    pengajars: [],
});
const romawi = (num) => {
    let roman = {
        M: 1000,
        CM: 900,
        D: 500,
        CD: 400,
        C: 100,
        XC: 90,
        L: 50,
        XL: 40,
        X: 10,
        IX: 9,
        V: 5,
        IV: 4,
        I: 1,
    };

    let str = "";
    for (var i of Object.keys(roman)) {
        var q = Math.floor(num / roman[i]);
        num -= q * roman[i];
        str += i.repeat(q);
    }

    return str;
};

const simpan = async () => {
    loading.value = true;
    let fd = new FormData();
    rombel.value.sekolah_id = page.props.sekolahs[0].npsn;
    Object.keys(rombel.value).forEach((k) => {
        fd.append(k, rombel.value[k]);
    });
    let url = rombel.value.id
        ? "dashboard.rombel.update"
        : "dashboard.rombel.store";
    // let httpMethod = sekolah.value.id ? 'put' : 'post'
    if (rombel.value.id) {
        fd.append("_method", "PUT");
    }

    router.post(route(url), fd, {
        onSuccess: (page) => {
            // console.log(res)
            ElNotification({
                title: "Info",
                message: "Data Rombel disimpan",
                type: "success",
            });
            closeMe();
        },
        onError: (err) => {
            // console.log(err)
            Object.keys(err).forEach((k) => {
                setTimeout(() => {
                    ElNotification({
                        title: "Error",
                        message: err[k],
                        type: "error",
                    });
                }, 500);
            });
            loading.value = false;
        },
    });
};

const onLogoPicked = (e) => {
    const file = e.target.files[0];
    let url = URL.createObjectURL(file);
    fileLogo.value = file;
    logoUrl.value = url;
    // console.log(e)
};

const closeMe = () => {
    loading.value = false;
    emit("close");
};

const getGurus = async () => {
    axios
        .post(
            route("dashboard.guru.show", {
                _query: {
                    sekolah: page.props.auth.roles.includes("admin")
                        ? "all"
                        : page.props.sekolahs[0].id,
                },
            }),
        )
        .then((res) => {
            gurus.value = res.data.gurus;
        });
};

const setFase = (tingkat) => {
    return parseInt(tingkat) <= 2 ? "A" : parseInt(tingkat) <= 4 ? "B" : "C";
};

watch(
    () => rombel,
    (newV, oldV) => {
        if (!props.selectedRombel) {
            rombel.value.pararel = newV.value.pararel
                ? newV.value.pararel
                : /[a-zA-Z]/gi.test(newV.value.kode.substring(-1))
                  ? newV.value.kode.substring(-1)
                  : "0";
            rombel.value.kode =
                page.props.sekolahs[0].npsn +
                "-" +
                page.props.periode.tapel.kode +
                "-" +
                newV.value.tingkat +
                (newV.value.pararel == "0" || !newV.value.pararel
                    ? ""
                    : newV.value.pararel.toLocaleLowerCase());
            rombel.value.label =
                "Kelas " +
                romawi(newV.value.tingkat) +
                ` (${toString(newV.value.tingkat)}` +
                (newV.value.pararel == "0"
                    ? ""
                    : " " + newV.value.pararel.toUpperCase()) +
                ")";
            rombel.value.tapel = page.props.periode.tapel.kode;
            rombel.value.fase = setFase(newV.value.tingkat);
        }
    },
    { deep: true },
);

onBeforeMount(() => {
    if (props.selectedRombel !== null) {
        rombel.value = props.selectedRombel;
    }

    getGurus();
});

const toString = (num) => {
    const text = ["SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM"];
    return text[num - 1];
};
</script>

<template>
    <!-- <h1>Form Sekolah {{ props.open }}</h1> -->
    <el-dialog
        v-model="show"
        width="800"
        title="Formulir Data Rombel"
        @close="closeMe"
        draggable
    >
        <!-- {{ gurus }} -->
        <el-form label-position="top" size="small">
            <el-row :gutter="6">
                <el-col :span="4">
                    <el-form-item label="Tingkat">
                        <el-select
                            v-model="rombel.tingkat"
                            placeholder="Pilih Tingkat"
                            required
                        >
                            <el-option
                                v-for="tingkat of 6"
                                :key="tingkat"
                                :value="tingkat"
                                :label="toString(tingkat)"
                            />
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="4">
                    <el-form-item label="Pararel">
                        <el-select
                            v-model="rombel.pararel"
                            placeholder="Pilih Pararel"
                            required
                        >
                            <el-option
                                v-for="par of ['0', 'a', 'b', 'c', 'd']"
                                :key="par"
                                :value="par"
                                :label="par.toUpperCase()"
                            />
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Nama Rombel">
                        <el-input
                            v-model="rombel.label"
                            placeholder="Nama Rombel"
                            required
                        />
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Kode Rombel">
                        <el-input
                            v-model="rombel.kode"
                            placeholder="Kode Rombel"
                            required
                        />
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row :gutter="6">
                <el-col :span="12">
                    <el-form-item label="Wali Kelas">
                        <el-select
                            v-model="rombel.guru_id"
                            placeholder="Wali Kelas"
                            filterable
                        >
                            <el-option
                                v-for="(guru, g) in gurus"
                                :key="g"
                                :value="guru.id"
                                :label="`${guru.nip}|${guru.nama}`"
                            />
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Pengajar">
                        <el-select
                            v-model="rombel.pengajars"
                            placeholder="Tambahkan Pengajar"
                            multiple
                            filterable
                        >
                            <el-option
                                v-for="(guru, g) in gurus"
                                :key="g"
                                :value="guru.nip"
                                :label="`${guru.nip}|${guru.nama}`"
                            />
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row justify="center">
                <el-button type="primary" :loading="loading" @click="simpan"
                    >Simpan</el-button
                >
            </el-row>
        </el-form>
    </el-dialog>
</template>
