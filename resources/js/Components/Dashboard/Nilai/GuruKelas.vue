<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { ElCard } from 'element-plus'
const page = usePage()

</script>

<template>
    <div>
        <el-card>
            <template #header>
                <div class="card-toolbar flex items-center justify-between">
                    <div class="title">
                        <h3 class="text-lg font-bold">Penilaian {{page.props.rombels[0].label}} Semester {{page.props.periode.semester.label}} {{ page.props.periode.tapel.deskripsi }}</h3>
                    </div>
                    <div class="toolbar-items">
                        
                    </div>
                </div>
            </template>
            <div class="card-body">

                <!-- {{ page.props.rombels }} -->
                <el-table :data="page.props.datas">
                    <el-table-column label="Mata Pelajaran" prop="label" />
                    <el-table-column label="Kategori" prop="kategori" />
                    <el-table-column label="Entri Nilai">
                        <template #default="scope">
                            <span class="flex items-center">
                                <el-button type="primary" rounded size="small">Nilai Harian</el-button>
                                <el-button type="primary" rounded size="small">PTS</el-button>
                                <el-button type="primary" rounded size="small">PAS</el-button>
                            </span>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </el-card>
    </div>
</template>