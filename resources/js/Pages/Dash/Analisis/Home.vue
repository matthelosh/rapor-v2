<script setup>
import DashLayout from "@/Layouts/DashLayout.vue";
import { usePage } from "@inertiajs/vue3";
import { ref, defineAsyncComponent } from "vue";

const props = defineProps({ asesmens: Array });
const page = usePage();

const FormKunciJawaban = defineAsyncComponent(() =>
    import('@/Components/Dashboard/Asesmen/FormKunciJawaban.vue')
);


const selectedAsesmen = ref(null);
const showFormKunci = ref(false);
const inputKunci = (row) => {
    selectedAsesmen.value = row;
    showFormKunci.value = true;
};

const active = ref(0)
const activeType = ref(null)
const setActive = (tipe, i) => {
    active.value = i
    activeType.value = tipe
}
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
        </template>
    </DashLayout>
    <Teleport to="body">
        <FormKunciJawaban :asesmen="selectedAsesmen" v-if="showFormKunci" :open="showFormKunci" @close="showFormKunci = !showFormKunci" />
    </Teleport>
</template>
