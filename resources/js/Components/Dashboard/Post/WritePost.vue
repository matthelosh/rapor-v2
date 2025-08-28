<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
// import { ElementTiptap } from 'element-tiptap-vue3-fixed'
// import 'element-tiptap-vue3-fixed/lib/style.css'
import {
    Doc,
    Text,
    History,
    Paragraph,
    Dropcursor,
    FontFamily,
    Bold,
    Underline,
    Italic,
    Strike,
    Heading,
    Link,
    Color,
    BulletList,
    OrderedList,
    TextAlign,
    LineHeight,
    Indent,
    Blockquote,
    Image,
    Table,
    Iframe,
    HorizontalRule,
    Fullscreen,
    Print,
    SelectAll,
    CodeView,
    CodeBlock,
    Code,
} from "element-tiptap-vue3-fixed";
import { ElNotification } from "element-plus";
import axios from "axios";
import codemirror from "codemirror";
import "codemirror/lib/codemirror.css"; // import base style
import "codemirror/mode/xml/xml.js"; // language
import "codemirror/addon/selection/active-line.js"; // require active-line.js
import "codemirror/addon/edit/closetag.js";

const page = usePage();
const files = ref([]);
// editor extensions
// they will be added to menubar and bubble menu by the order you declare.
const extensions = [
    Doc,
    Text,
    History,
    Dropcursor,
    Paragraph.configure({ bubble: true }),
    Heading.configure({ bubble: true }),
    FontFamily,
    Color.configure({ bubble: true }),
    Underline.configure({ bubble: true }),
    Bold.configure({ bubble: true }),
    Italic.configure({ bubble: true }),
    TextAlign.configure({ bubble: true }),
    Indent,
    Blockquote,
    LineHeight,
    Link.configure({ bubble: true }),
    Strike.configure({ bubble: true }),
    BulletList,
    OrderedList,
    Image.configure({
        defaultWidth: 400,
        draggable: true,
        uploadRequest: async (file) => {
            let fd = new FormData();
            fd.append("image", file);
            return new Promise(async (resolve, reject) => {
                await axios
                    .post(route("dashboard.post.image.upload"), fd)
                    .then((res) => {
                        resolve(res.data.url);
                    });
            });
        },
    }),
    Table,
    Iframe,
    HorizontalRule,
    Print,
    SelectAll,
    Fullscreen,
    CodeView.configure({
        codemirror,
        codemirrorOptions: {
            styleActiveLine: true,
            autoCloseTags: false,
        },
    }),
    CodeBlock,
    Code,
];

const fileImage = ref(null);
const props = defineProps({ selectedPost: Object });
const emit = defineEmits(["close"]);
const post = ref({
    content: "<h1>Halo</h1>",
});

const content = ref("Halo");
const loading = ref(false);
const simpan = async () => {
    router.post(
        route("dashboard.post.store"),
        { data: post.value },
        {
            onStart: () => (loading.value = true),
            onSuccess: (page) => {
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: "success",
                });
            },
            onError: (errs) => {
                Object.keys(errs).forEach((k) => {
                    setTimeout(() => {
                        ElNotification({
                            title: "Error",
                            message: errs[k],
                            type: "error",
                        });
                    }, 1000);
                });
            },
        },
    );
};

const onCoverChange = async (e) => {
    const file = e.target.files[0];
    // post.value.cover = URL.createObjectURL(file)
    // fileImage.value = file
    let fd = new FormData();
    fd.append("image", file);
    await axios.post(route("dashboard.post.image.upload"), fd).then((res) => {
        post.value.cover = res.data.url;
    });
};

const listFiles = async () => {
    axios.post(route("dashboard.post.image.list")).then((res) => {
        files.value = res.data.files;
    });
};
const fileBrowser = ref(false);
const toggleFileBrowser = () => (fileBrowser.value = !fileBrowser.value);
onBeforeMount(() => {
    listFiles();
    post.value = props.selectedPost ?? {};
});
</script>

<template>
    <el-card>
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Tulis Artikel</h3>
                <div class="toolbar-items flex items-center gap-2">
                    <el-button type="primary" @click="simpan">Simpan</el-button>
                    <el-button type="danger" @click="emit('close')" circle>
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <template #default>
            <el-scrollbar max-height="60vh">
                <div class="post-writer grid grid-cols-8 gap-4">
                    <div class="writer col-span-6">
                        <el-input
                            v-model="post.title"
                            placeholder="Judul Tulisan"
                            style="
                                margin-bottom: 20px;
                                font-size: 1.5em;
                                outline: none;
                                height: 40px;
                                font-weight: bold;
                                color: #345676;
                                border: none;
                            "
                        ></el-input>
                        <element-tiptap
                            v-model:content="post.content"
                            :extensions="extensions"
                        />
                    </div>
                    <div
                        class="post-meta col-span-2 border p-2 bg-slate-50 shadow"
                    >
                        <el-divider>Properti Tulisan</el-divider>
                        <h3>Cover Tulisan</h3>
                        <input
                            type="file"
                            ref="fileCover"
                            accept=".jpg, .jpeg, .JPG, .JPEG, .png, .PNG, .bmp, .BMP, .svg, .SVG"
                            @change="onCoverChange"
                            class="hidden"
                        />
                        <div
                            class="cover-box hover:cursor-pointer"
                            @click="$refs.fileCover.click()"
                        >
                            <el-image
                                :src="post.cover"
                                fit="scale-down"
                                style="
                                    width: 100%;
                                    height: 150px;
                                    background: #efefef;
                                    border-radius: 5px;
                                    border: 1px solid #ddf;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                "
                            >
                                <template #error>
                                    <Icon
                                        icon="mdi:image-edit"
                                        class="text-8xl text-sky-300"
                                    />
                                </template>
                            </el-image>
                        </div>
                        <h3 class="mt-4">Kategori Tulisan</h3>
                        <el-select
                            v-model="post.category"
                            placeholder="Kategori"
                        >
                            <el-option
                                v-for="(kat, k) in page.props.categories"
                                :key="k"
                                :value="kat.name"
                            ></el-option>
                        </el-select>
                        <h3 class="mt-4">Tipe Tulisan</h3>
                        <el-select v-model="post.type" placeholder="Kategori">
                            <el-option
                                v-for="(tipe, t) in ['post', 'page']"
                                :value="tipe"
                            ></el-option>
                        </el-select>
                    </div>
                </div>
            </el-scrollbar>
        </template>
        <template #footer>
            <div class="footer-items flex justify-between mb-4">
                <div></div>
                <el-button @click="toggleFileBrowser">File Tersimpan</el-button>
            </div>
            <div class="file-list bg-slate-200 p-4" v-if="fileBrowser">
                <template v-for="(file, f) in files" :key="f">
                    <el-image
                        :src="file.url"
                        style="margin: 10px; width: 100px; cursor: pointer"
                        width="200px"
                    >
                        <template #error>
                            {{ file.name }}
                        </template>
                    </el-image>
                </template>
            </div>
        </template>
    </el-card>
</template>
