<script setup>
import DashLayout from "@/Layouts/DashLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({ asesmens: Array });
const page = usePage();

const tipe_soal = ref({
    pg: "Pilihan Ganda",
    pgk: "Pilihan Ganda Kompleks",
    ps: "Pasangan",
    bs: "Benar Salah",
    is: "Isian",
    ur: "Uraian",
});

const selectedAsesmen = ref(null);
const showAnalises = (row) => {};
const showFormKunci = ref(false);
const inputKunci = (row) => {
    selectedAsesmen.value = row;
    showFormKunci.value = true;
};

const selectedTypes = ref([]);
const kunci = ref({});

const simpan = async () => {};
</script>

<template>
    <DashLayout>
        <template #header> Analisis Asesmen </template>
        <template #default>
            <el-card>
                <template #header
                    >Data Asesmen {{ page.props.periode.tapel.label }}</template
                >
                <template #default>
                    <!-- {{ props.asesmens }} -->
                    <el-table :data="props.asesmens">
                        <el-table-column
                            label="#"
                            type="index"
                            fixed="left"
                        ></el-table-column>
                        <el-table-column label="Nama" prop="nama" fixed="left">
                        </el-table-column>
                        <el-table-column
                            label="Kode"
                            prop="kode"
                        ></el-table-column>
                        <el-table-column label="Tanggal" prop="tanggal">
                        </el-table-column>
                        <el-table-column label="Mapel" prop="mapel.label">
                        </el-table-column>
                        <el-table-column label="Agama" prop="agama">
                        </el-table-column>
                        <el-table-column label="Tingkat" prop="tingkat">
                        </el-table-column>
                        <el-table-column label="Analisis">
                            <template #default="{ row }">
                                <span>{{ row.analises.length }}</span>
                            </template>
                        </el-table-column>
                        <el-table-column label="Opsi" fixed="right">
                            <template #default="{ row }">
                                <span>
                                    <el-button @click="inputKunci(row)"
                                        >Kunci</el-button
                                    >
                                    <el-button @click="showAnalises(row)"
                                        >Analisa</el-button
                                    >
                                </span>
                            </template>
                        </el-table-column>
                    </el-table>
                </template>
            </el-card>
        </template>
    </DashLayout>
    <Teleport to="body">
        <el-dialog
            v-model="showFormKunci"
            :title="`Kunci Jawaban ${selectedAsesmen?.nama}`"
        >
            <el-form label-position="top">
                <div class="grid grid-cols-6 gap-2">
                    <div class="col-span-2">
                        <h3 class="mb-4 font-black text-lg">
                            Tambahkan Tipe Soal
                        </h3>
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
                    </div>
                    <div class="col-span-4">
                        <h3 class="mb-4 font-black text-lg text-center">
                            Tambahkan Kunci Jawaban
                        </h3>
                        <el-alert type="error" v-if="selectedTypes.length < 1">
                            Tambahkan Tipe soal dulu
                        </el-alert>
                        <div v-else class="px-4">
                            <template v-for="tipe in selectedTypes">
                                <el-form-item :label="tipe_soal[tipe]">
                                    <el-input
                                        type="textarea"
                                        v-model="kunci[tipe]"
                                    ></el-input>
                                </el-form-item>
                            </template>
                        </div>
                    </div>
                </div>
            </el-form>
            <template #footer>
                <div class="flex justify-end p-4">
                    <el-button
                        :native-type="null"
                        type="primary"
                        @click="simpan"
                        >Simpan</el-button
                    >
                </div>
            </template>
        </el-dialog>
    </Teleport>
</template>
