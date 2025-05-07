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

    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes("node_modules")) {
                        return id
                            .toString()
                            .split("node_modules/")[1]
                            .split("/")[0]
                            .toString();
                    }
                },
                assetFileNames: (assetInfo) => {
                    return assetInfo.name == "app.css"
                        ? "assets/app.css"
                        : "assets/" + assetInfo.name;
                },
            },
        },
        // minify: process.env.APP_ENV !== 'local' ? true : false,
        // cssCodeSplit: process.env.APP_ENV === 'local' ? false : undefined
    },
});
