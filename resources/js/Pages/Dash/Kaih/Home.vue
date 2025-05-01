<script setup>
import { ref, defineAsyncComponent } from "vue";
import DashLayout from "@/Layouts/DashLayout.vue";

const FormSemester = defineAsyncComponent(
    () => import("@/Components/Dashboard/Kaih/Semester.vue"),
);
defineProps({ rombels: Array });
const selectedRombel = ref(null);
const formSemester = ref(false);
const selectedSiswa = ref(null);
const showSemester = (item, rombel) => {
    selectedSiswa.value = item;
    formSemester.value = true;
    selectedRombel.value = {
        kode: rombel.kode,
        label: rombel.label,
        tapel: rombel.tapel,
    };
};
</script>

<template>
    <DashLayout title="7 KAIH">
        <template #header>
            <h3>7 KAIH</h3>
        </template>
        <template #default>
            <el-card body-class="bg-slate-200">
                <template #header>
                    <div class="toolbar flex items-center justify-between">
                        <h3>Monitor Progress 7 KAIH</h3>
                        <div
                            class="toolbar-items flex items-center gap-2"
                        ></div>
                    </div>
                </template>
                <template #default>
                    <el-collapse>
                        <template
                            v-for="(rombel, r) in rombels"
                            :key="`rombel-${r}`"
                        >
                            <el-collapse-item :title="rombel.label">
                                <el-table
                                    :data="rombel.siswas"
                                    max-height="600"
                                >
                                    <el-table-column label="#" type="index" />
                                    <el-table-column label="NISN" prop="nisn" />
                                    <el-table-column label="Nama" prop="nama" />
                                    <el-table-column label="Progress KAIH">
                                        <template #default="{ row }">
                                            <div
                                                class="bar flex gap-2 items-end"
                                            >
                                                <template
                                                    v-for="k in Object.keys(
                                                        row.kaihs,
                                                    )"
                                                    :key="`kaih-${k}`"
                                                >
                                                    <ElTooltip :content="k">
                                                        <div
                                                            class="bar-item w-[50px] flex items-end justify-center cursor-pointer font-bold text-slate-600 rounded hover:shadow hover:-translate-y-2 transition-transform duration-300"
                                                            :class="
                                                                row.kaihs[k] <=
                                                                30
                                                                    ? 'bg-red-300'
                                                                    : row.kaihs[
                                                                            k
                                                                        ] <= 60
                                                                      ? 'bg-yellow-300'
                                                                      : 'bg-green-400'
                                                            "
                                                            :style="`height: ${row.kaihs[k]}px;`"
                                                        >
                                                            {{ row.kaihs[k] }}
                                                        </div>
                                                    </ElTooltip>
                                                </template>
                                            </div>
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="Opsi">
                                        <template #default="{ row }">
                                            <div class="flex">
                                                <el-button>Detail</el-button>
                                                <el-button>Bulanan</el-button>
                                                <el-button
                                                    @click="
                                                        showSemester(
                                                            row,
                                                            rombel,
                                                        )
                                                    "
                                                    >Semester</el-button
                                                >
                                            </div>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </el-collapse-item>
                        </template>
                    </el-collapse>
                </template>
            </el-card>
        </template>
    </DashLayout>
    <el-dialog v-model="formSemester" fullscreen>
        <template #default>
            <FormSemester
                :siswa="selectedSiswa"
                :rombel="selectedRombel"
                v-if="formSemester"
            />
        </template>
    </el-dialog>
</template>
