<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

import DashLayout from '@/Layouts/DashLayout.vue'
const page = usePage()

const activeCollapse = ref(0)

const mapels = (tingkat) => {
    return page.props.mapels.filter(mapel => mapel.tingkat == tingkat)
}

const cetak = async(target) => {
    const host = window.location.host
    let cssUrl = page.props.app_env == 'local' ? 'http://localhost:5173/resources/css/app.css' : `/build/assets/app.css`
    const el = document.querySelector(`.${target}`)
	let html = `<!doctype html>
				<html>
					<head>
						<title class="uppercase">Ledger Nilai ${page.props.periode.tapel.deskripsi}</title>
						<link rel="stylesheet" href="${cssUrl}" />
					</head>
					<body>
						${el.outerHTML}
					</body>

				</html>
	`
    let win = window.open(host+'/print',"_blank","height=900,width=1000")
	await win.document.write(html)
    setTimeout(() => {
        win.print();
        win.close();
    }, 1500);
    
}

onBeforeMount(async() => {

})
</script>

<template>
<Head title="Ledger Nilai" />

<DashLayout>
    <template #header>
        <h3>Data Ledger Nilai </h3>
    </template>
    <el-card>
        <template #header>
            <h2 class="text-lg font-bold text-slate-500">Ledger Nilai</h2>
        </template>
        <div class="card-body">
            <el-collapse v-model="activeCollapse">
                <template v-for="(rombel, r) in page.props.rombels" :key="rombel.kode">
                    <el-collapse-item :name="r">
                        <template #title>
                            <div class="collapse-title">
                                <h3 class="uppercase">{{ rombel.label }} {{ rombel.sekolah.nama }}</h3>
                            </div>
                        </template>
                        <div class="collapse-body border-t border-black print:border-none pt-4" :class="`cetak-${rombel.kode}`">
                            <div class="table-header flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold uppercase print:text-center">Ledger Nilai {{ rombel.label }} {{ page.props.sekolahs[0].nama }} {{ page.props.periode.tapel.deskripsi }}</h3>
                                <el-button type="primary" @click="cetak(`cetak-${rombel.kode}`)" class="print:hidden">
                                    <Icon icon="mdi:printer" />
                                </el-button>
                            </div>
                            <table width="100%" class="uppercase print:text-[10pt]">
                                <thead >
                                    <tr>
                                        <th rowspan="3" class="border border-black">No</th>
                                        <th rowspan="3" class="border border-black">NISN</th>
                                        <th rowspan="3" class="border border-black">Nama</th>
                                        <template v-for="(mapel,m) in page.props.mapels" :key="m">
                                            <th class="border border-black p-1 text-xs"  colspan="2">{{ mapel.label }}</th>
                                        </template>
                                    </tr>
                                    <tr>
                                        <template v-for="(mapel,m) in page.props.mapels" :key="m">
                                            <th class="border border-black" colspan="2">Semester</th>
                                        </template>
                                    </tr>
                                    <tr>
                                        <template v-for="(mapel,m) in page.props.mapels" :key="m">
                                            <th class="border border-black" >1</th>
                                            <th class="border border-black" >2</th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(nilai, n) in page.props.nilais" :key="n">
                                        <tr>
                                            <td class="border border-black text-center w-[50px]">{{ n+1 }}</td>
                                            <td class="border border-black px-2 w-[100px]">{{ nilai.nisn }}</td>
                                            <td class="border border-black px-2 ">{{ nilai.nama }}</td>
                                            <template v-for="(mapel,m) in page.props.mapels" :key="m">
                                                <td  class="border border-black text-center w-[50px]">{{ nilai[mapel.kode]?.sem1 }}</td>
                                                <td  class="border border-black text-center w-[50px]">{{ nilai[mapel.kode]?.sem2 }}</td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </el-collapse-item>
                </template>
            </el-collapse>
        </div>

        <!-- <div>{{ page.props.nilais }}</div> -->
    </el-card>
    <!-- {{page.props.mapels}} -->

</DashLayout>
</template>