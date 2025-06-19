<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import axios from "axios";
import { ElNotification } from "element-plus";

const page = usePage();
const props = defineProps({ siswa: Object, rombel: Object, open: Boolean });
const emit = defineEmits(["close", "nextSiswa", "prevSiswa"]);
const ekskuls = ref([]);
const nilais = ref([]);

const getEkskuls = async () => {
    await axios
        .get(
            route("dashboard.pembelajaran.ekskul", {
                _query: {
                    sekolahId: page.props.sekolahs[0].npsn,
                },
            }),
        )
        .then((res) => {
            ekskuls.value = res.data.ekskuls;
            res.data.ekskuls.forEach((item) => {
                nilais.value.push({
                    ekskulId: item.id,
                    nilai: "",
                    deskripsi: "",
                });
            });
        })
        .catch((err) => console.log(err));
};

const getNilai = async () => {
    await axios
        .get(
            route("dashboard.nilai.ekskul.index", {
                _query: {
                    sekolahId: page.props.sekolahs[0].npsn,
                    siswaId: props.siswa.nisn,
                    tapel: page.props.periode.tapel.kode,
                    semester: page.props.periode.semester.kode,
                },
            }),
        )
        .then((res) => {
            if (res.data.nilais || res.data.nilais.length > 0) {
                res.data.nilais.forEach((nilai) => {
                    const index = nilais.value.findIndex(
                        (n) => n.ekskulId == nilai.ekskul_id,
                    );
                    nilais.value[index].nilai = nilai.nilai;
                    nilais.value[index].deskripsi = nilai.deskripsi;
                    // console.log(index)
                });
            }
            // ekskuls.value = res.data.ekskuls
            // res.data.ekskuls.forEach(item => {
            //     nilais.value.push({ekskulId: item.id, nilai: '', deskripsi: ''})
            // })
            // console.log(res)
        })
        .catch((err) => console.log(err));
};

const hapus = async (id) => {
    ElLoading.service({
        text: "Loading...",
        background: "rgba(0, 0, 0, 0.7)",
    });
    axios
        .delete(route("dashboard.nilai.ekskul.destroy", { id: id }))
        .then((res) => {
            ElNotification({
                title: "Success",
                message: "Data berhasil dihapus",
                type: "success",
            });
        })
        .catch((err) => {
            ElNotification({
                title: "Error",
                message: err.response.data.message,
                type: "error",
            });
        })
        .finally(() => {
            ElLoading.service().close();
        });
};

const simpan = async () => {
    router.post(
        route("dashboard.nilai.ekskul.store", {
            _query: {
                siswaId: props.siswa.nisn,
                tapel: page.props.periode.tapel.kode,
                semester: page.props.periode.semester.kode,
            },
        }),
        { nilais: nilais.value },
        {
            onSuccess: (page) => {
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: " success",
                });
            },
            onError: (errs) => {
                Object.keys(errs).forEach((k) => {
                    setTimeout(() => {
                        ElNotification({
                            title: "Error",
                            message: errs[k],
                            type: "error",
                        });
                    }, 500);
                });
            },
        },
    );
};

onBeforeMount(async () => {
    await getEkskuls();
    getNilai();
});
</script>

<template>
    <el-dialog
        v-model="props.open"
        :show-close="false"
        :body-style="{ background: '#aaa' }"
    >
        <template #header="{ close }">
            <div class="toolbar flex items-center justify-between">
                <span class="uppercase"
                    >Isi Nilai Ekstrakurikuler {{ props.siswa.nama }}</span
                >
                <div class="toolbar-items flex items-center">
                    <el-button
                        type="danger"
                        @click="emit('close')"
                        size="small"
                    >
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <div class="dialog-body">
            <el-table :data="ekskuls">
                <el-table-column
                    label="Nama Ekskul"
                    prop="nama"
                    width="200"
                ></el-table-column>
                <el-table-column label="Nilai" width="150">
                    <template #default="scope">
                        <el-select
                            v-model="nilais[scope.$index].nilai"
                            placeholder="Pilih Nilai"
                        >
                            <el-option
                                v-for="nilai in ['A', 'B', 'C', 'D']"
                                :key="nilai"
                                :value="nilai"
                            />
                        </el-select>
                    </template>
                </el-table-column>
                <el-table-column label="Deskripsi">
                    <template #default="scope">
                        <el-input
                            type="textarea"
                            rows="1"
                            autosize
                            v-model="nilais[scope.$index].deskripsi"
                            placeholder="Isikan deskripsi capaian"
                        ></el-input>
                    </template>
                </el-table-column>
                <el-table-column label="Opsi">
                    <template #default="{ row }">
                        <el-button
                            type="danger"
                            @click="hapus(row.id)"
                            size="small"
                        >
                            <Icon icon="mdi:delete" />
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <template #footer>
            <div class="flex justify-end px-4">
                <el-button type="primary" @click="simpan">Simpan</el-button>
            </div>
        </template>
    </el-dialog>
</template>
