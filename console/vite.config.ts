import {resolve} from "node:path";
import {loadEnv} from "vite";
import vueJsx from "@vitejs/plugin-vue-jsx";
import vue from "@vitejs/plugin-vue";
import Components from "unplugin-vue-components/vite";
import {AntDesignVueResolver} from "unplugin-vue-components/resolvers";
import Unocss from "unocss/vite";
import {createSvgIconsPlugin} from "vite-plugin-svg-icons";
import dayjs from "dayjs";
import pkg from "./package.json";
import type {UserConfig, ConfigEnv} from "vite";

const CWD = process.cwd();

const __APP_INFO__ = {
    pkg,
    lastBuildTime: dayjs().format("YYYY-MM-DD HH:mm:ss"),
};

// https://vitejs.dev/config/
export default ({mode}: ConfigEnv): UserConfig => {
    // 环境变量
    const {VITE_BASE_URL} = loadEnv(mode, CWD);

    return {
        base: VITE_BASE_URL,
        define: {
            __APP_INFO__: JSON.stringify(__APP_INFO__),
        },
        resolve: {
            alias: [
                {
                    find: "@",
                    replacement: resolve(__dirname, "./src"),
                },
            ],
        },
        plugins: [
            vue(),
            Unocss(),
            vueJsx({
                // options are passed on to @vue/babel-plugin-jsx
            }),
            createSvgIconsPlugin({
                // Specify the icon folder to be cached
                iconDirs: [resolve(CWD, "src/assets/icons")],
                // Specify symbolId format
                symbolId: "svg-icon-[dir]-[name]",
            }),
            Components({
                dts: "types/components.d.ts",
                types: [
                    {
                        from: "./src/components/basic/button/",
                        names: ["AButton"],
                    },
                    {
                        from: "vue-router",
                        names: ["RouterLink", "RouterView"],
                    },
                ],
                resolvers: [
                    AntDesignVueResolver({
                        importStyle: false, // css in js
                        exclude: ["Button"],
                    }),
                ],
            }),
        ],
        css: {
            preprocessorOptions: {
                less: {
                    javascriptEnabled: true,
                    modifyVars: {},
                    // additionalData: `
                    //   @import '@/styles/variables.less';
                    // `,
                },
            },
        },
        server: {
            host: "0.0.0.0",
            port: 8088,
            open: true,
            proxy: {
                "^/api": {
                    target: "http://127.0.0.1:19878/v1/console",
                    changeOrigin: true,
                    rewrite: (path) => path.replace(/^\/api/, ""),
                },
                "^/upload": {
                    target: "https://nest-api.buqiyuan.site/upload",
                    changeOrigin: true,
                    rewrite: (path) => path.replace(new RegExp(`^/upload`), ""),
                },
            },
            // 提前转换和缓存文件以进行预热。可以在服务器启动时提高初始页面加载速度，并防止转换瀑布。
            warmup: {
                // 请注意，只应该预热频繁使用的文件，以免在启动时过载 Vite 开发服务器
                // 可以通过运行 npx vite --debug transform 并检查日志来找到频繁使用的文件
                clientFiles: ["./index.html", "./src/{components,api}/*"],
            },
        },
        optimizeDeps: {
            include: ["lodash-es", "ant-design-vue/es/locale/zh_CN"],
        },
        esbuild: {
            supported: {
                // https://github.com/vitejs/vite/pull/8665
                "top-level-await": true,
            },
        },
        build: {
            minify: "esbuild",
            cssTarget: "chrome89",
            chunkSizeWarningLimit: 2000,
            rollupOptions: {
                output: {
                    // minifyInternalExports: false,
                    manualChunks(id) {
                        //TODO fix circular imports
                        if (id.includes("node_modules/ant-design-vue/")) {
                            return "antdv";
                        } else if (/node_modules\/(vue|vue-router|pinia)\//.test(id)) {
                            return "vue";
                        }
                    },
                },
                onwarn(warning, rollupWarn) {
                    // ignore circular dependency warning
                    if (
                        warning.code === "CYCLIC_CROSS_CHUNK_REEXPORT" &&
                        warning.exporter?.includes("src/api/")
                    ) {
                        return;
                    }
                    rollupWarn(warning);
                },
            },
        },
    };
};
