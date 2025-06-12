<script setup>
import DashLayout from "@/Layouts/DashLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";

const props = defineProps({ asesmens: Array });
const page = usePage();

const FormKunciJawaban = defineAsyncComponent(
    () => import("@/Components/Dashboard/Asesmen/FormKunciJawaban.vue"),
);

const FormAnalisis = defineAsyncComponent(
    () => import("@/Components/Dashboard/Asesmen/Analisis.vue"),
);

const selectedAsesmen = ref(null);
const showFormKunci = ref(false);
const inputKunci = (row) => {
    selectedAsesmen.value = row;
    showFormKunci.value = true;
};

// Analisis
const formAnalisis = ref(false);
const showAnalises = (item) => {
    selectedAsesmen.value = item;
    formAnalisis.value = true;
};

const closeAnalisis = () => {
    selectedAsesmen.value = null;
    formAnalisis.value = false;
};
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
                        <el-table-column label="Analisis & Kunci">
                            <template #default="{ row }">
                                <span
                                    >{{ row.analises?.id }} |
                                    {{ row.kunci?.id }}</span
                                >
                            </template>
                        </el-table-column>
                        <el-table-column label="Opsi" fixed="right">
                            <template #default="{ row }">
                                <el-button-group size="small">
                                    <el-button @click="inputKunci(row)"
                                        >Kunci</el-button
                                    >
                                    <el-button @click="showAnalises(row)"
                                        >Analisa</el-button
                                    >
                                </el-button-group>
                            </template>
                        </el-table-column>
                    </el-table>
                </template>
            </el-card>
            <Teleport to="body">
                <FormKunciJawaban
                    :asesmen="selectedAsesmen"
                    v-if="showFormKunci"
                    :open="showFormKunci"
                    @close="showFormKunci = !showFormKunci"
                />
            </Teleport>

            <Teleport to="body">
                <FormAnalisis
                    :asesmen="selectedAsesmen"
                    v-if="formAnalisis"
                    :open="formAnalisis"
                    @close="closeAnalisis"
                />
            </Teleport>
        </template>
    </DashLayout>
</template>
