<script setup>
import { ref, computed } from "vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import DashLayout from "@/Layouts/DashLayout.vue";
import { fotoSiswa } from "@/helpers/Gambar";
import { capitalize } from "@/helpers/String";
const page = usePage();
</script>

<template>
    <DashLayout>
        <Head title="Buku Induk" />
        <template #header>BUKU INDUK</template>
        <div>
            <el-card>
                <div
                    class="h-12 flex items-center justify-between bg-slate-100 shadow mb-2 rounded-lg"
                ></div>
                <el-table :data="page.props.siswas.data">
                    <el-table-column label="No" type="index" fixed="left" />

                    <el-table-column label="Foto" fixed="left" width="70">
                        <template #default="{ row }">
                            <el-avatar>
                                <img :src="fotoSiswa(row)" />
                            </el-avatar>
                        </template>
                    </el-table-column>
                    <el-table-column label="Nama" fixed="left" width="200">
                        <template #default="{ row }">
                            {{ row.nama.toUpperCase() }}
                        </template>
                    </el-table-column>
                    <el-table-column label="NISN/NIS" width="120">
                        <template #default="{ row }">
                            {{ row.nisn }}/ {{ row.nis ?? "-" }}
                        </template>
                    </el-table-column>
                    <el-table-column label="JK" width="60">
                        <template #default="{ row }">
                            {{ row.jk == "Laki-laki" ? "Lk" : "Pr" }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Tempat Lahir" width="120">
                        <template #default="{ row }">
                            {{ row.tempat_lahir.toUpperCase() }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Tanggal Lahir" width="120">
                        <template #default="{ row }">
                            {{ row.tanggal_lahir }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Alamat" width="250">
                        <template #default="{ row }">
                            {{ capitalize(row.alamat) }}, RT.
                            {{ row.rt ?? "-" }} RW {{ row.rw ?? "-" }},
                            {{ capitalize(row.desa) }}, Kec.
                            {{ capitalize(row.kecamatan) }}, Kab/Kota
                            {{ row.kabupaten }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Telp/HP" width="130">
                        <template #default="{ row }">
                            {{ row.hp }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Orang Tua" width="130">
                        <template #default="{ row }">
                            <div v-if="row.ortus"></div>
                            <div v-else></div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Status" width="80">
                        <template #default="{ row }">
                            <el-tag
                                :type="
                                    row.status == 'aktif'
                                        ? 'success'
                                        : row.status == 'lulus'
                                          ? 'primary'
                                          : 'error'
                                "
                            >
                                {{ row.status.toUpperCase() }}
                            </el-tag>
                        </template>
                    </el-table-column>
                </el-table>
            </el-card>
        </div>
    </DashLayout>
</template>
