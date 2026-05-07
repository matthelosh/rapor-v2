<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import axios from "axios";

import { fotoGuru } from "@/helpers/Gambar";

const page = usePage();

const data = computed(() => page.props.data);

const profil = ref({});

onBeforeMount(() => {
    axios
        .get(page.props.appUrl + "/userdetail")
        .then((res) => {
            profil.value = res.data.userdetail;
        })
        .catch((err) => console.log(err));
});
</script>

<template>
    <el-row :gutter="20">
        <el-col :span="6">
            <el-card
                style="background-color: #ffffffba; backdrop-filter: blur(10px)"
            >
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
            <el-card
                style="background-color: #ffffffba; backdrop-filter: blur(10px)"
            >
                <template #header>
                    <span>Data Rombel</span>
                </template>
                <div class="card-body">
                    <table class="w-full text-gray-600 text-sm">
                        <thead>
                            <tr>
                                <th
                                    class="border-b-2 p-2 border-double border-black"
                                >
                                    #
                                </th>
                                <th
                                    class="border-b-2 p-2 border-double border-black text-left"
                                >
                                    Rombel
                                </th>
                                <th
                                    class="border-b-2 p-2 border-double border-black text-left"
                                >
                                    Wali Kelas
                                </th>
                                <th
                                    class="border-b-2 p-2 border-double border-black"
                                >
                                    Jumlah Siswa
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template
                                v-for="(rombel, r) in data.sekolah.rombels"
                                :key="r"
                            >
                                <tr>
                                    <td
                                        class="border-b border-gray-400 text-center p-2"
                                    >
                                        {{ r + 1 }}
                                    </td>
                                    <td class="border-b border-gray-400 p-2">
                                        {{ rombel.label }}
                                    </td>
                                    <td class="border-b border-gray-400 p-2">
                                        {{ rombel.wali_kelas.nama }},
                                        {{ rombel.wali_kelas.gelar_belakang }}
                                    </td>
                                    <td
                                        class="border-b border-gray-400 p-2 text-center"
                                    >
                                        <el-tag type="primary">
                                            {{
                                                rombel.siswas
                                                    ? rombel.siswas.filter(
                                                          (s) =>
                                                              s.jk ==
                                                              "Laki-laki",
                                                      ).length
                                                    : "0"
                                            }}
                                        </el-tag>
                                        <el-tag type="danger">
                                            {{
                                                rombel.siswas
                                                    ? rombel.siswas.filter(
                                                          (s) =>
                                                              s.jk ==
                                                              "Perempuan",
                                                      ).length
                                                    : "0"
                                            }}
                                        </el-tag>
                                        <el-tag
                                            type="success"
                                            class="font-bold"
                                        >
                                            {{ rombel.siswas?.length }}
                                        </el-tag>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </el-card>
        </el-col>
        <el-col :span="6">
            <el-card
                style="background-color: #ffffffba; backdrop-filter: blur(10px)"
            >
                <template #header>
                    <span>Profil Guru</span>
                </template>
                <div class="card-body">
                    <img
                        :src="fotoGuru(profil)"
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
                                <td>{{ profil?.status?.toUpperCase() }}</td>
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
