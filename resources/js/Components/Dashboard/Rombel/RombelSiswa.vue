<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import axios from "axios";
import { read, utils } from "xlsx";
import { ElNotification } from "element-plus";

const page = usePage();

const props = defineProps({ show: Boolean, selectedRombel: Object });
const emit = defineEmits(["close", "refresh"]);
const loading = ref(false);
const members = ref([]);
const searchMember = ref("");
const selectedMembers = ref([]);
const byRombel = ref(null);
const nonMembers = ref([]);
const searchNonMember = ref("");
const fitleredMembers = computed(() => {
    return members.value.filter(
        (data) =>
            !searchMember.value ||
            data.nama.toLowerCase().includes(searchMember.value.toLowerCase()),
    );
});
const filteredNonMembers = computed(() => {
    return nonMembers.value.filter(
        (data) =>
            !searchNonMember.value ||
            data.nama
                .toLowerCase()
                .includes(searchNonMember.value.toLowerCase()),
    );
});
const selectedNonMembers = ref([]);

const selectionNonMember = (val) => {
    selectedNonMembers.value = val;
};

const selectionMember = (val) => {
    selectedMembers.value = val;
};

const keluarkan = async () => {
    await router.post(
        route("dashboard.rombel.member.detach", {
            id: props.selectedRombel.id,
        }),
        { siswas: selectedMembers.value },
        {
            onSuccess: (page) => {
                emit("refresh");
                selectedMembers.value.forEach((siswa) => {
                    let memberIndex = members.value.findIndex(
                        (item) => item.id === siswa.id,
                    );
                    members.value.splice(memberIndex, 1);
                    nonMembers.value.push(siswa);
                });

                // getNonMember()
            },
        },
    );
};

const assignMember = async () => {
    await router.post(
        route("dashboard.rombel.member.attach", {
            id: props.selectedRombel.id,
        }),
        { siswas: selectedNonMembers.value },
        {
            onSuccess: (page) => {
                emit("refresh");
                selectedNonMembers.value.forEach((siswa) => {
                    let nonMemberIndex = nonMembers.value.findIndex(
                        (item) => item.id === siswa.id,
                    );
                    nonMembers.value.splice(nonMemberIndex, 1);
                    members.value.push(siswa);
                });
                // getNonMember()
            },
        },
    );
};

const getNonMember = async () => {
    await axios
        .post(
            route("dashboard.siswa.nonmember", {
                _query: {
                    rombelId: props.selectedRombel.id,
                    targetRombel: byRombel.value,
                },
            }),
        )
        .then((res) => {
            nonMembers.value = res.data.siswas;
        })
        .catch((err) => console.log(err));
};

const kirim = async () => {};

const onFileSiswaChange = async (e) => {
    const file = e.target.files[0];
    const ab = await file.arrayBuffer();
    const wb = read(ab);
    const ws = wb.Sheets[wb.SheetNames[0]];
    utils.sheet_to_json(ws).forEach((siswa) => {
        nonMembers.value.forEach((nm) => {
            if (siswa.nisn == nm.nisn) {
                // members.value.push(nm)
                // selectionNonMember([nm])
                selectedNonMembers.value.push(nm);
            }
        });
    });
};

const otherRombels = ref([]);
const getOtherRombels = async () => {
    loading.value = true;
    axios
        .get(
            route("dashboard.rombel.index", {
                _query: {
                    currentRombel: props.selectedRombel.id,
                    sekolahId: page.props.sekolahs[0].npsn,
                },
            }),
        )
        .then((res) => {
            otherRombels.value = res.data.rombels;
            loading.value = false;
        });
};

onMounted(() => {
    getNonMember();
    members.value = props.selectedRombel.siswas;
    getOtherRombels();
});
</script>

<template>
    <el-dialog v-model="props.show" fullscreen @close="emit('close')">
        <template #header>
            <div class="flex items-center justify-between">
                <div class="dialog-title">
                    Manajemen Peserta Didik {{ props.selectedRombel.label }}
                </div>
            </div>
        </template>
        <el-scrollbar height="90vh">
            <div class="dialog-body bg-slate-100 py-2">
                <el-row :gutter="12">
                    <el-col :span="12">
                        <el-card>
                            <template #header>
                                <div class="flex items-center justify-between">
                                    <div class="card-title flex items-center">
                                        <Icon
                                            icon="mdi:human-child"
                                            class="mb-1 text-lg"
                                        />
                                        Data Peserta Didik
                                        {{ props.selectedRombel.label }}
                                    </div>
                                    <div class="card-toolbar flex">
                                        <input
                                            type="file"
                                            ref="fileSiswa"
                                            @change="onFileSiswaChange"
                                            v-if="!selectedMembers.length > 0"
                                        />
                                        <el-button type="primary" size="small"
                                            >Kirim</el-button
                                        >
                                        <el-button
                                            type="danger"
                                            size="small"
                                            v-if="selectedMembers.length > 0"
                                            @click="keluarkan"
                                            >Keluarkan</el-button
                                        >
                                    </div>
                                </div>
                                <el-input
                                    v-model="searchMember"
                                    placeholder="Cari Siswa"
                                    class="mt-2"
                                />
                            </template>
                            <div class="card-body">
                                <el-table
                                    :data="fitleredMembers"
                                    @selection-change="selectionMember"
                                    height="65vh"
                                >
                                    <el-table-column
                                        type="selection"
                                        width="55"
                                    />
                                    <el-table-column label="NISN" prop="nisn" />
                                    <el-table-column label="Nama" prop="nama" />
                                    <el-table-column label="JK" prop="jk" />
                                </el-table>
                            </div>
                        </el-card>
                    </el-col>
                    <el-col :span="12">
                        <el-card>
                            <template #header>
                                <div class="flex items-center justify-between">
                                    <div class="card-title flex items-center">
                                        <Icon
                                            icon="mdi:human-child"
                                            class="mb-1 text-lg"
                                        />
                                        Siswa belum masuk Rombel
                                    </div>
                                    <div class="card-toolbar flex">
                                        <el-button
                                            type="primary"
                                            size="small"
                                            v-if="selectedNonMembers.length > 0"
                                            @click="assignMember"
                                            >Masukkan</el-button
                                        >
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <el-select
                                        v-model="byRombel"
                                        placeholder="Dari Rombel Lain"
                                        @change="getNonMember"
                                    >
                                        <el-option
                                            v-for="(rombel, r) in otherRombels"
                                            :key="r"
                                            :value="rombel.id"
                                            :label="`${rombel.tapel} | ${rombel.label}`"
                                        />
                                    </el-select>
                                    <el-input
                                        v-model="searchNonMember"
                                        placeholder="Cari Siswa"
                                        class="mt-2"
                                    />
                                </div>
                            </template>
                            <div class="card-body">
                                <el-table
                                    :data="filteredNonMembers"
                                    @selection-change="selectionNonMember"
                                    height="65vh"
                                >
                                    <el-table-column
                                        type="selection"
                                        width="55"
                                    />
                                    <el-table-column label="NISN" prop="nisn" />
                                    <el-table-column label="Nama" prop="nama" />
                                    <el-table-column label="JK" prop="jk" />
                                </el-table>
                            </div>
                        </el-card>
                    </el-col>
                </el-row>
            </div>
        </el-scrollbar>
    </el-dialog>
</template>
