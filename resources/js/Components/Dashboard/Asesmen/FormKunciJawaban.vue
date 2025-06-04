<script setup>
import { ref, computed, onBeforeMount, nextTick } from "vue";
import { router, usePage } from "@inertiajs/vue3";

const page = usePage();
const props = defineProps({ asesmen: Object, open: Boolean });
const emit = defineEmits(["close"]);
const show = ref(false);
const loading = ref(false);
const tipe_soal = ref({
    pg: "Pilihan Ganda",
    pgk: "Pilihan Ganda kompleks",
    ps: "Menjodohkan",
    bs: "Benar-Salah",
    is: "Isian",
    ur: "Uraian",
});
const kunci = ref({
    pg: {
        jml_soal: 0,
        kunci: [],
    },
    pgk: {
        jml_soal: 0,
        kunci: [],
    },
    ps: {
        jml_soal: 0,
        kunci: [],
    },
    bs: {
        jml_soal: 0,
        kunci: [],
    },
    is: {
        jml_soal: 0,
        kunci: [],
    },
    ur: {
        jml_soal: 0,
        kunci: [],
    },
});

const selectedTypes = ref([]);

// <Steps></Steps>
const active = ref(0);
const activeType = ref(null);
const setActive = (tipe, i) => {
    active.value = i;
    activeType.value = tipe;
};

//Form Kunci
const inputRefs = ref([]);
const validatePG = (val, index) => {
    if (["A", "B", "C", "D"].includes(val.toUpperCase())) {
        nextTick(() => {
            const nextInput = inputRefs[index + 1];
            if (nextInput) nextInput.focus();
        });
    } else {
        ElMessage({
            type: "error",
            message: "Jawaban hanya A,B,C atau D",
            duration: 1000,
        });
        kunci.value.pg.kunci[index] = "";
        nextTick(() => {
            inputRefs.value[index]?.focus();
        });
        return;
    }
};

const pgkRefs = ref([]);
const validatePGK = (index, val) => {
    const valids = ["AB", "AC", "AD", "BC", "BD", "CD"];
    if (!valids.includes(val.toUpperCase())) {
        ElMessage({
            type: "error",
            message: `Jawaban hanya ${valids}`,
            duration: 1000,
        });
        kunci.value.pgk.kunci[index] = "";
        nextTick(() => {
            pgkRefs.value[index]?.focus();
        });
        return;
    }

    nextTick(() => {
        const nextInput = pgkRefs[index + 1];
        if (nextInput) nextInput.focus();
    });
};
const psRefs = ref([]);
const validatePS = (index, val) => {
    const valids = [
        "1A",
        "1B",
        "1C",
        "1D",
        "1E",
        "2A",
        "2B",
        "2C",
        "2D",
        "2E",
        "3A",
        "3B",
        "3C",
        "3D",
        "3E",
        "4A",
        "4B",
        "4C",
        "4D",
        "4E",
        "5A",
        "5B",
        "5C",
        "5D",
        "5E",
    ];
    if (!valids.includes(val.toUpperCase())) {
        ElMessage({
            type: "error",
            message: `Jawaban hanya ${valids}`,
            duration: 1000,
        });
        kunci.value.ps.kunci[index] = "";
        nextTick(() => {
            pgkRefs.value[index]?.focus();
        });
        return;
    }

    nextTick(() => {
        const nextInput = psRefs[index + 1];
        if (nextInput) nextInput.focus();
    });
};

const simpan = async () => {
    router.post(
        route("dashboard.asesmen.kunci.store", {
            kode: props.asesmen.kode,
        }),
        kunci.value,
        {
            onStart: () => (loading.value = true),
            onSuccess: () => {
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: "success",
                });
            },
            onError: (errs) => {
                Object.keys(errs).forEach((k) => {
                    ElNotification({
                        type: "error",
                        message: errs[k],
                        title: "Error",
                    });
                });
            },
            onFinish: () => (loading.value = false),
        },
    );
};
onBeforeMount(() => {
    show.value = props.open;
});
</script>

<template>
    <el-dialog
        v-model="show"
        :title="`Kunci Jawaban ${asesmen?.nama}`"
        @closed="emit('close')"
        id="dialog-form-kunci"
    >
        <el-form-item label="Tipe Soal">
            <el-select
                v-model="selectedTypes"
                multiple
                placeholder="Tambah Soal"
            >
                <el-option
                    v-for="k in Object.keys(tipe_soal)"
                    :value="k"
                    :label="`${k}: ${tipe_soal[k]}`"
                ></el-option>
            </el-select>
        </el-form-item>
        <el-steps :active="active">
            <template v-for="(tipe, i) in selectedTypes">
                <el-step
                    :title="`${tipe_soal[tipe]}`"
                    @click="setActive(tipe, i)"
                    style="cursor: pointer"
                >
                    <template #description> </template>
                </el-step>
            </template>
        </el-steps>
        <div
            v-if="activeType == 'pg'"
            class="p-2 border border-sky-400 rounded"
        >
            <el-form label-position="top">
                <el-form-item label="Jumlah Soal:">
                    <el-input
                        type="number"
                        v-model="kunci['pg'].jml_soal"
                        style="width: 60px"
                    />
                </el-form-item>
                <h3 class="font-bold">Kunci Jawaban:</h3>
                <div
                    class="kunci flex flex-wrap gap-1"
                    v-if="kunci['pg'].jml_soal > 0"
                >
                    <div
                        class="flex flex-col w-10"
                        v-for="(item, i) in Array.from({
                            length: kunci['pg'].jml_soal,
                        })"
                    >
                        <el-form-item
                            :label="(i + 1).toString()"
                            :prop="`pg${i}`"
                        >
                            <el-input
                                class="text-center"
                                :validate-event="true"
                                v-model="kunci['pg'].kunci[i]"
                                maxlength="1"
                                @input="validatePG($event, i)"
                                :ref="(el) => (inputRefs[i] = el)"
                            />
                        </el-form-item>
                    </div>
                </div>
            </el-form>
        </div>
        <div
            class="p-2 border border-sky-400 rounded"
            v-if="activeType == 'pgk'"
        >
            <el-form label-position="top">
                <el-form-item label="Jumlah Soal:">
                    <el-input
                        type="number"
                        v-model="kunci['pgk'].jml_soal"
                        style="width: 60px"
                    />
                </el-form-item>
                <h3 class="fotn-bold">Kunci Jawaban</h3>
                <div
                    class="flex gap-1 flex-wrap"
                    v-if="kunci['pgk'].jml_soal > 0"
                >
                    <div
                        class="kunci flex flex-col gap-1"
                        v-for="(item, i) in Array.from({
                            length: kunci['pgk'].jml_soal,
                        })"
                    >
                        <el-form-item :label="(i + 1).toString()">
                            <el-input
                                v-model="kunci['pgk'].kunci[i]"
                                style="width: 60px"
                                @change="(val) => validatePGK(i, val)"
                                :ref="(el) => (pgkRefs[i] = el)"
                            />
                        </el-form-item>
                    </div>
                </div>
            </el-form>
        </div>
        <div
            class="p-2 border border-sky-400 rounded"
            v-if="activeType == 'ps'"
        >
            <el-form label-position="top">
                <el-form-item label="Jumlah Soal:">
                    <el-input
                        type="number"
                        v-model="kunci['ps'].jml_soal"
                        style="width: 50px"
                    />
                </el-form-item>
                <h3 class="font-bold">Kunci Jawaban</h3>
                <div
                    class="flex gap-1 flex-wrap"
                    v-if="kunci['ps'].jml_soal > 0"
                >
                    <div
                        class="kunci flex flex-col gap-1"
                        v-for="(item, i) in Array.from({
                            length: kunci['ps'].jml_soal,
                        })"
                    >
                        <el-form-item :label="(i + 1).toString()">
                            <el-input
                                v-model="kunci['ps'].kunci[i]"
                                style="width: 60px"
                                @change="(val) => validatePS(i, val)"
                                :ref="(el) => (psRefs[i] = el)"
                            />
                        </el-form-item>
                    </div>
                </div>
            </el-form>
        </div>
        <div
            v-if="activeType == 'is'"
            class="p-2 border rounded border-sky-500"
        >
            <el-form-item label="Jumlah Soal:">
                <el-input
                    type="number"
                    v-model="kunci['is'].jml_soal"
                    style="width: 50px"
                />
            </el-form-item>
            <h3 class="font-bold">Kunci Jawaban</h3>
            <template
                v-for="(item, i) in Array.from({
                    length: kunci['is'].jml_soal,
                })"
            >
                <el-form-item :label="(i + 1).toString()">
                    <el-input
                        placeholder="Masukkan Kunci"
                        v-model="kunci['is'][i]"
                        style="border: none; outline: none"
                    />
                </el-form-item>
            </template>
        </div>
        <div
            v-if="activeType == 'ur'"
            class="p-2 border rounded border-sky-500"
        >
            <el-form-item label="Jumlah Soal:">
                <el-input
                    type="number"
                    v-model="kunci['ur'].jml_soal"
                    style="width: 50px"
                />
            </el-form-item>
            <h3 class="font-bold">Kunci Jawaban</h3>
            <template
                v-for="(item, i) in Array.from({
                    length: kunci['ur'].jml_soal,
                })"
            >
                <el-form-item :label="(i + 1).toString()">
                    <el-input
                        type="textarea"
                        placeholder="Masukkan Kunci"
                        v-model="kunci['ur'][i]"
                        style="border: none; outline: none"
                    />
                </el-form-item>
            </template>
        </div>

        <template #footer>
            <div class="flex justify-end p-4">
                <el-button :native-type="null" type="primary" @click="simpan"
                    >Simpan</el-button
                >
            </div>
        </template>
    </el-dialog>
</template>

<style>
#dialog-form-kunci .el-input__wrapper {
    padding: 4px !important;
    border: none;
    outline: none;
    text-align: center;
}
</style>
