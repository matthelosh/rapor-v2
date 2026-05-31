import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import { ElementPlusResolver } from "unplugin-vue-components/resolvers";

export default defineConfig({
    
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        AutoImport({
            resolvers: [
                ElementPlusResolver({
                    importStyle: "css",
                }),
            ],
        }),
        Components({
            dirs: ["resources/js/Components"],
            resolvers: [ElementPlusResolver()],
        }),
    ],
    server: {
        allowedHosts: ['pkgwagir.or.id','pkgwagir.test']
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes("node_modules")) {
                        const module = id.toString().split("node_modules/")[1].split("/")[0];
                        
                        // Pisahkan library berat ke chunk terpisah
                        if (["xlsx", "codemirror"].includes(module)) {
                            return "vendor-heavy";
                        }
                        if (["html2canvas", "jspdf"].includes(module)) {
                            return "vendor-pdf";
                        }
                        if (["element-plus"].includes(module)) {
                            return "vendor-element";
                        }
                        
                        return module;
                    }
                },
                assetFileNames: (assetInfo) => {
                    return assetInfo.name == "app.css"
                        ? "assets/app.css"
                        : "assets/" + assetInfo.name;
                },
            },
        },
    },
});