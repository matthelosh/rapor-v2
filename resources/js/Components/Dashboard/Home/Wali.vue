<script setup>
import { ref, computed } from "vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import { avatar } from "@/helpers/Gambar";

const page = usePage();

const data = computed(() => page.props.data);

const profil = computed(() => page.props.auth.user.userable);
</script>

<template>
    <el-row :gutter="20">
        <el-col :span="6">
            <el-card>
                <template #header>
                    <span>Data Mapel</span>
                </template>
                <div class="card-body">
                    <el-table :data="data.sekolah.mapels">
                        <el-table-column
                            label="#"
                            type="index"
                        ></el-table-column>
                        <el-table-column
                            label="Nama"
                            prop="label"
                        ></el-table-column>
                    </el-table>
                </div>
            </el-card>
        </el-col>
        <el-col :span="12">
            <el-card>
                <template #header>
                    <span>Data Rombel</span>
                </template>
                <div class="card-body">
                    <el-table :data="data.rombels">
                        <el-table-column
                            label="#"
                            type="index"
                        ></el-table-column>
                        <el-table-column
                            label="Nama"
                            prop="label"
                        ></el-table-column>
                        <el-table-column label="Siswa">
                            <template #default="scope">
                                <span>{{ scope.row.siswas.length }}</span>
                            </template>
                        </el-table-column>
                        <el-table-column label="Lembaga">
                            <template #default="scope">
                                <span>{{ scope.row.sekolah.nama }}</span>
                            </template>
                        </el-table-column>
                    </el-table>
                </div>
            </el-card>
        </el-col>
        <el-col :span="6">
            <el-card>
                <template #header>
                    <span>Profil Guru</span>
                </template>
                <div class="card-body">
                    <img
                        :src="avatar(profil)"
                        alt="Foto Profil"
                        class="rounded-full w-[60%] mx-auto mb-4"
                    />
                    <el-divider>Biodata</el-divider>
                    <table>
                        <tbody>
                            <tr>
                                <td>Gelar Depan</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.gelar_depan ?? "-" }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.nama }}</td>
                            </tr>
                            <tr>
                                <td>Gelar Belakang</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.gelar_belakang ?? "-" }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.nip }}</td>
                            </tr>
                            <tr>
                                <td>NUPTK</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.nuptk ?? "-" }}</td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.jk }}</td>
                            </tr>
                            <tr>
                                <td>Agama</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.agama }}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.alamat }}</td>
                            </tr>
                            <tr>
                                <td>Status Kepegawaian</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.status.toUpperCase() }}</td>
                            </tr>
                            <tr>
                                <td>Pangkat</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.pangkat ?? "-" }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td class="px-1">:</td>
                                <td>{{ profil.jabatan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </el-card>
        </el-col>
    </el-row>
</template>
