<script setup>
import DashLayout from "@/Layouts/DashLayout.vue";

const props = defineProps({ rombels: Array });
</script>

<template>
    <DashLayout title="Akhir Jenjang">
        <template #header>
            <span>Administrasi Akhir Jenjang</span>
        </template>
        <div
            class="card p-4 rounded shadow bg-white bg-opacity-70 backdrop-blur max-w-full"
        >
            <!-- {{ rombels }} -->
            <el-collapse accordion>
                <template v-for="(rombel, r) in rombels" :key="`rombel-${r}`">
                    <el-collapse-item>
                        <template #title>
                            <span
                                class="px-4 font-bold uppercase font-['Arial']"
                            >
                                {{ rombel.label }}
                            </span>
                        </template>
                        <div class="p-4">
                            <h3>Data Siswa</h3>
                            <el-table :data="rombel.siswas">
                                <el-table-column
                                    label="#"
                                    type="index"
                                    fixed="left"
                                />
                                <el-table-column
                                    label="Nama"
                                    prop="nama"
                                    fixed="left"
                                />
                                <el-table-column label="NISN" prop="nisn" />
                                <el-table-column label="JK" prop="jk" />
                                <el-table-column
                                    label="Opsi"
                                    fixed="right"
                                    width="600"
                                >
                                    <template #default="{ row }">
                                        <el-button-group size="small">
                                            <el-button>Rekap Rapor</el-button>
                                            <el-button>PSAJ</el-button>
                                            <el-button>PSAJ Praktik</el-button>
                                            <el-button>Transkrip</el-button>
                                            <el-button>S K L</el-button>
                                            <el-button>Suket</el-button>
                                            <el-button>SK NISN</el-button>
                                            <el-button>SK Prestasi</el-button>
                                        </el-button-group>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </el-collapse-item>
                </template>
            </el-collapse>
        </div>
    </DashLayout>
</template>
