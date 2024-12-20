<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { writeFile, utils } from "xlsx";
import DashLayout from "@/Layouts/DashLayout.vue";
const page = usePage();

const activeCollapse = ref(0);

const loading = ref(false);
const mapels = (tingkat) => {
    return page.props.mapels.filter((mapel) => mapel.tingkat == tingkat);
};

const unduh = async (target, rombel, tapel) => {
    loading.value = true;
    const el = document.querySelector(`.${target}`);
    const table = el.querySelector("table");
    let wb = await utils.table_to_book(table);
    await writeFile(
        wb,
        `Ledger Nilai ${rombel} - ${page.props.sekolahs[0].nama} ${page.props.periode.tapel.deskripsi}.xlsx`
    );
    setTimeout(() => {
        loading.value = false;
    }, 1000);
};

const cetak = async (target) => {
    const host = window.location.host;
    let cssUrl =
        page.props.app_env == "local"
            ? "http://localhost:5173/resources/css/app.css"
            : `/build/assets/app.css`;
    const el = document.querySelector(`.${target}`);
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
	`;
    let win = window.open(host + "/print", "_blank", "height=900,width=1000");
    await win.document.write(html);
    setTimeout(() => {
        win.print();
        win.close();
    }, 1500);
};

const rankMe = (nilai, sem) => {
    const list = page.props.nilais.lists[sem - 1];
    let sorted = list.sort((a, b) => b - a);
    let index = sorted.findIndex((n) => n == parseInt(nilai));
    return [...list].reduce((a, c) => a + c, 0) > 0 ? index + 1 : index;
};

onBeforeMount(async () => {});
</script>

<template>
    <Head title="Ledger Nilai" />

    <DashLayout>
        <template #header>
            <h3>Data Ledger Nilai</h3>
        </template>
        <el-card>
            <template #header>
                <h2 class="text-lg font-bold text-slate-500">Ledger Nilai</h2>
            </template>
            <div class="card-body">
                <el-collapse v-model="activeCollapse">
                    <template
                        v-for="(rombel, r) in page.props.rombels"
                        :key="rombel.kode"
                    >
                        <el-collapse-item :name="r">
                            <template #title>
                                <div class="collapse-title">
                                    <h3 class="uppercase">
                                        {{ rombel.label }}
                                        {{ rombel.sekolah.nama }}
                                    </h3>
                                </div>
                            </template>
                            <div
                                class="collapse-body relative border-t border-black print:border-none pt-4"
                                :class="`cetak-${rombel.kode}`"
                            >
                                <div
                                    class="table-header flex items-center justify-between mb-4"
                                >
                                    <h3
                                        class="text-lg font-bold uppercase print:text-center"
                                    >
                                        Ledger Nilai {{ rombel.label }}
                                        {{ page.props.sekolahs[0].nama }}
                                        {{ page.props.periode.tapel.deskripsi }}
                                    </h3>
                                    <div class="flex items-center">
                                        <el-button
                                            type="primary"
                                            @click="
                                                cetak(`cetak-${rombel.kode}`)
                                            "
                                            class="print:hidden"
                                        >
                                            <Icon
                                                icon="mdi:printer"
                                                class="mr-1"
                                            />
                                            Cetak
                                        </el-button>
                                        <el-button
                                            type="primary"
                                            @click="
                                                unduh(
                                                    `cetak-${rombel.kode}`,
                                                    rombel.label,
                                                    rombel.tapel
                                                )
                                            "
                                            class="print:hidden"
                                        >
                                            <Icon
                                                icon="mdi:download"
                                                class="mr-1"
                                            />
                                            Unduh
                                        </el-button>
                                    </div>
                                </div>
                                <table
                                    width="100%"
                                    class="uppercase print:text-[10pt]"
                                >
                                    <thead class="sticky top-30">
                                        <tr>
                                            <th
                                                rowspan="3"
                                                class="border border-black"
                                            >
                                                No
                                            </th>
                                            <th
                                                rowspan="3"
                                                class="border border-black"
                                            >
                                                NISN
                                            </th>
                                            <th
                                                rowspan="3"
                                                class="border border-black"
                                            >
                                                Nama
                                            </th>
                                            <template
                                                v-for="(mapel, m) in page.props
                                                    .mapels"
                                                :key="m"
                                            >
                                                <th
                                                    class="border border-black p-1 text-xs"
                                                    colspan="2"
                                                >
                                                    {{ mapel.label }}
                                                </th>
                                            </template>
                                            <th
                                                class="bg-slate-300 border border-black p-1 text-xs"
                                                colspan="2"
                                            >
                                                Total
                                            </th>
                                            <th
                                                class="bg-slate-300 border border-black p-1 text-xs"
                                                colspan="2"
                                            >
                                                Rangking
                                            </th>
                                        </tr>
                                        <tr>
                                            <template
                                                v-for="(mapel, m) in page.props
                                                    .mapels"
                                                :key="m"
                                            >
                                                <th
                                                    class="border border-black"
                                                    colspan="2"
                                                >
                                                    Semester
                                                </th>
                                            </template>
                                            <th
                                                class="bg-slate-300 border border-black"
                                                colspan="2"
                                            >
                                                Semester
                                            </th>
                                            <th
                                                class="bg-slate-300 border border-black"
                                                colspan="2"
                                            >
                                                Semester
                                            </th>
                                        </tr>
                                        <tr>
                                            <template
                                                v-for="(mapel, m) in page.props
                                                    .mapels"
                                                :key="m"
                                            >
                                                <th class="border border-black">
                                                    1
                                                </th>
                                                <th class="border border-black">
                                                    2
                                                </th>
                                            </template>
                                            <th
                                                class="bg-slate-300 border border-black"
                                            >
                                                1
                                            </th>
                                            <th
                                                class="bg-slate-300 border border-black"
                                            >
                                                2
                                            </th>
                                            <th
                                                class="bg-slate-300 border border-black"
                                            >
                                                1
                                            </th>
                                            <th
                                                class="bg-slate-300 border border-black"
                                            >
                                                2
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template
                                            v-for="(nilai, n) in page.props
                                                .nilais.datas"
                                            :key="n"
                                        >
                                            <tr>
                                                <td
                                                    class="border border-black text-center w-[50px]"
                                                >
                                                    {{ n + 1 }}
                                                </td>
                                                <td
                                                    class="border border-black px-2 w-[100px]"
                                                >
                                                    {{ nilai.nisn }}
                                                </td>
                                                <td
                                                    class="border border-black px-2"
                                                >
                                                    {{ nilai.nama }}
                                                </td>
                                                <template
                                                    v-for="(mapel, m) in page
                                                        .props.mapels"
                                                    :key="m"
                                                >
                                                    <td
                                                        class="border border-black text-center w-[50px]"
                                                    >
                                                        {{
                                                            nilai[mapel.kode]
                                                                ?.sem1
                                                        }}
                                                    </td>
                                                    <td
                                                        class="border border-black text-center w-[50px]"
                                                    >
                                                        {{
                                                            nilai[mapel.kode]
                                                                ?.sem2
                                                        }}
                                                    </td>
                                                </template>
                                                <td
                                                    class="bg-slate-300 border border-black text-center w-[50px]"
                                                >
                                                    {{ nilai["sum1"] }}
                                                </td>
                                                <td
                                                    class="bg-slate-300 border border-black text-center w-[50px]"
                                                >
                                                    {{ nilai["sum2"] }}
                                                </td>
                                                <td
                                                    class="bg-slate-300 border border-black text-center w-[50px]"
                                                >
                                                    {{
                                                        rankMe(nilai["sum1"], 1)
                                                    }}
                                                </td>
                                                <td
                                                    class="bg-slate-300 border border-black text-center w-[50px]"
                                                >
                                                    {{
                                                        rankMe(nilai["sum2"], 2)
                                                    }}
                                                </td>
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
        <Teleport to="body">
            <div
                class="z-[999999] backdrop-blur fixed top-0 right-0 bottom-0 left-0 bg-slate-700 bg-opacity-30 flex items-center justify-center"
                v-if="loading"
            >
                <Icon
                    icon="mdi:loading"
                    class="animate-spin text-8xl text-white"
                />
            </div>
        </Teleport>
    </DashLayout>
</template>
