<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3';

import DashLayout from '@/Layouts/DashLayout.vue';
import { ElNotification } from 'element-plus';
import { Doc, Text, History, Paragraph, Dropcursor, FontFamily, Bold, Underline, Italic, Strike, Heading, Link, Color, BulletList, OrderedList, TextAlign, LineHeight, Indent, Blockquote, Image, Table, Iframe, HorizontalRule, Fullscreen, Print, SelectAll, CodeView, CodeBlock, Code } from 'element-tiptap-vue3-fixed'
import codemirror from 'codemirror';
import 'codemirror/lib/codemirror.css'; // import base style
import 'codemirror/mode/xml/xml.js'; // language
import 'codemirror/addon/selection/active-line.js'; // require active-line.js
import 'codemirror/addon/edit/closetag.js'; 
import axios from 'axios';
const page = usePage()
const mode = ref('list')
const loading = ref(false)
const tps = ref([])
defineProps({canAddSoal: Boolean})

const extensions = [
    Doc, Text, History, Dropcursor,
    Paragraph.configure({bubble: true}), Heading.configure({bubble: true}),FontFamily, Color.configure({bubble: true}), Underline.configure({bubble: true}), Bold.configure({bubble: true}), Italic.configure({bubble: true}),  TextAlign.configure({bubble: true}),Indent, Blockquote, LineHeight, Link.configure({bubble: true}), Strike.configure({bubble: true}), BulletList, OrderedList,
    Image.configure({
        defaultWidth: 400,
        draggable: true,
        uploadRequest: async(file) => {
            let fd = new FormData()
            fd.append("image", file)
            return new Promise(async(resolve, reject) => {
                await axios.post(route('dashboard.soal.image.upload'), fd).then(res => {
                    resolve(res.data.url)
                })
            })
        }
    }),
    Table, Iframe, HorizontalRule, Print, SelectAll, Fullscreen,
    CodeView.configure({
        codemirror,
        codemirrorOptions: {
            styleActiveLine: true,
            autoCloseTags: false,
        },
    }),
    CodeBlock, Code
]
const pilihanextensions = [
    Doc, Text, History, Dropcursor,
    Paragraph.configure({bubble: true}),  Color.configure({bubble: true}), BulletList, OrderedList,
    Image.configure({
        defaultWidth: 400,
        draggable: true,
        uploadRequest: async(file) => {
            let fd = new FormData()
            fd.append("image", file)
            return new Promise(async(resolve, reject) => {
                await axios.post(route('dashboard.soal.image.upload'), fd).then(res => {
                    resolve(res.data.url)
                })
            })
        }
    }),
    Table,
]

const soals = computed(() => page.props.soals)

const soal = ref({
    pertanyaan: 'Tulis Pertanyaan',
    tipe: 'pilihan',
    level: 'MOT'
})
const addSoal = () => {
    mode.value = 'form'
}

const closeForm = () => {
    mode.value = 'list'
}

const simpanSoal = async () => {
    router.post(route('dashboard.soal.store'), soal.value, {
        onStart: () => loading.value = true,
        onSuccess: () => {
            router.reload({only: ['soals']})
            ElNotification({title: 'Info', message: page.props.flash.message, type: 'success'})
        }, 
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500);
            })
        },
        onFinish: () => loading.value = false
    })
}

const rules = ref({
    nama: [
        { required: true}
    ]
})

const getTps = async () => {
    loading.value = true
    axios.post(route('dashboard.pembelajaran.tp.index'), {
        
        mapelId: soal.value.mapel_id,
        tingkat: soal.value.tingkat,
        semester: soal.value.semester,
        agama: soal.value.mapel_id == 'pabp' ? page.props.auth.user.userable.agama : null
    
    }).then(res => {
        tps.value = res.data.tps
    }).catch(err => {
        console.log(err)
    }).finally(() => loading.value = false)
}



</script>

<template>
<Head title="Bank Soal" />
<DashLayout>
    <template #header>Bank Soal</template>
    <el-card>
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Data Soal </h3>
                <div class="flex items-center gap-1">
                    <el-button type="primary" size="small" @click="addSoal" :disabled="!canAddSoal" v-if="mode == 'list'">Tambah Soal</el-button>
                    <el-button type="danger" size="small" @click="closeForm" :disabled="!canAddSoal" v-if="mode == 'form'">Batal</el-button>
                </div>
            </div>
        </template>
        <div class="list" v-if="mode == 'list'">
            <el-table :data="soals">
                <el-table-column label="#" type="index"></el-table-column>
                <el-table-column label="Pertanyaan">
                    <template #default="{row}">
                        <div v-html="row.pertanyaan"></div>
                    </template>
                </el-table-column>
                <el-table-column label="A" >
                    <template #default="{row}">
                        <div v-html="row.a"></div>
                    </template>
                </el-table-column>
                <el-table-column label="B" >
                    <template #default="{row}">
                        <div v-html="row.b"></div>
                    </template>
                </el-table-column>
                <el-table-column label="C" >
                    <template #default="{row}">
                        <div v-html="row.c"></div>
                    </template>
                </el-table-column>
                <el-table-column label="D" >
                    <template #default="{row}">
                        <div v-html="row.d"></div>
                    </template>
                </el-table-column>
                <el-table-column label="Kunci" >
                    <template #default="{row}">
                        {{ row.kunci }}
                    </template>
                </el-table-column>
                <el-table-column label="Semester" >
                    <template #default="{row}">
                        {{ row.semester }}
                    </template>
                </el-table-column>
                <el-table-column label="Agama" >
                    <template #default="{row}">
                        {{ row.agama }}
                    </template>
                </el-table-column>
                <el-table-column label="Tipe" >
                    <template #default="{row}">
                        {{ row.tipe }}
                    </template>
                </el-table-column>
                <el-table-column label="Level" >
                    <template #default="{row}">
                        {{ row.level }}
                    </template>
                </el-table-column>
                <el-table-column label="Opsi" >
                    <template #default="scope">
                        
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <div class="form w-[60%] bg-slate-100 shadow p-4 mx-auto" v-if="mode == 'form'">
            <h1 class="text-lg font-bold text-sky-700 text-center uppercase mb-4">Formulir Soal</h1>
            <el-form v-model="soal" label-position="top" v-loading="loading" :rules="rules">
                <el-row :gutter=20 justify="center">
                    <el-col :span="4">
                        <el-form-item label="Mapel">
                            <el-select v-model="soal.mapel_id" placeholder="Pilih Mapel">
                                <el-option v-for="mapel in page.props.sekolahs[0].mapels" :value="mapel.kode" :label="mapel.label"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="4">
                        <el-form-item label="Tipe Soal">
                            <el-select v-model="soal.tipe" placeholder="Pilih Tipe">
                                <el-option v-for="tipe in ['pilihan','isian', 'uraian']" :value="tipe"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="4">
                        <el-form-item label="Semester">
                            <el-select v-model="soal.semester" placeholder="Pilih Semester" :disabled="!soal.mapel_id">
                                <el-option v-for="sem in ['1','2']" :value="sem" :label="`Semester ${sem}`"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="4">
                        <el-form-item label="Tingkat">
                            <el-select :disabled="!soal.semester" v-model="soal.tingkat" placeholder="Pilih Kelas" @change="getTps">
                                <el-option v-for="tingkat in ['1','2','3','4','5','6']" :value="tingkat" :label="`Kelas ${tingkat}`"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="4">
                        <el-form-item label="Level Soal">
                            <el-select v-model="soal.level" placeholder="Pilih Level">
                                <el-option v-for="level in ['LOT', 'MOT', 'HOT']" :value="level"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter=20 justify="center" v-if="tps.length > 0">
                    <el-col>
                        <el-select v-model="soal.tp_id" filterable >
                            <el-option v-for="tp in tps" :value="tp.id" :label="tp.teks"></el-option>
                        </el-select>
                    </el-col>
                </el-row>
                <el-row :gutter=20 justify="center">
                    <el-col>
                        <el-form-item label="Pertanyaan">
                            <element-tiptap v-model:content="soal.pertanyaan" :extensions="extensions" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <h3 class="text-lg font-bold">Pilihan Jawaban:</h3>
                <el-row :gutter=20 justify="center">
                    <el-col>
                        <el-form-item label="Pilihan A">
                            <element-tiptap v-model:content="soal.a" :extensions="pilihanextensions" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter=20 justify="center">
                    <el-col>
                        <el-form-item label="Pilihan B">
                            <element-tiptap v-model:content="soal.b" :extensions="pilihanextensions" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter=20 justify="center">
                    <el-col>
                        <el-form-item label="Pilihan C">
                            <element-tiptap v-model:content="soal.c" :extensions="pilihanextensions" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter=20 justify="center">
                    <el-col>
                        <el-form-item label="Pilihan D">
                            <element-tiptap v-model:content="soal.d" :extensions="pilihanextensions" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter=20 justify="center">
                    <el-col>
                        
                        <el-form-item label="Kunci Jawaban" justify="center">
                            <el-radio-group v-model="soal.kunci">
                                <el-radio border v-for="kunci in ['a', 'b', 'c', 'd']" :value="kunci">{{ kunci.toUpperCase() }}</el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter=20 justify="center">
                    <el-button type="primary" @click="simpanSoal">Simpan</el-button>
                </el-row>
            </el-form>
        </div>
    </el-card>
</DashLayout>
</template>