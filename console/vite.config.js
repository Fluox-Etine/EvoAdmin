import { fileURLToPath, URL } from 'node:url';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx';
import unocss from 'unocss/vite';
import { createSvgIconsPlugin } from 'vite-plugin-svg-icons';
import { resolve } from "node:path";
import { AntDesignVueResolver } from "unplugin-vue-components/resolvers";
import Components from 'unplugin-vue-components/vite';
import topLevelAwait from 'vite-plugin-top-level-await';
var CWD = process.cwd();
// https://vitejs.dev/config/
export default (function () {
    return {
        resolve: {
            alias: {
                '@': fileURLToPath(new URL('./src', import.meta.url))
            }
        },
        plugins: [
            vue(),
            vueJsx(),
            unocss(),
            createSvgIconsPlugin({
                // Specify the icon folder to be cached
                iconDirs: [resolve(CWD, 'src/assets/icons')],
                // Specify symbolId format
                symbolId: 'svg-icon-[dir]-[name]',
            }),
            Components({
                dts: 'types/components.d.ts',
                types: [
                    {
                        from: './src/components/core/button/',
                        names: ['AButton'],
                    },
                    {
                        from: 'vue-router',
                        names: ['RouterLink', 'RouterView'],
                    },
                ],
                resolvers: [
                    AntDesignVueResolver({
                        importStyle: false, // css in js
                        exclude: ['Button'],
                    }),
                ],
            }),
            topLevelAwait({
                // The export name of top-level await promise for each chunk module
                promiseExportName: '__tla',
                // The function to generate import names of top-level await promise in each chunk module
                promiseImportName: function (i) { return "__tla_".concat(i); }
            })
        ],
        server: {
            host: '0.0.0.0',
            port: 8088,
            open: true,
            proxy: {
                '^/api': {
                    target: 'http://127.0.0.1:19878/v1/console',
                    changeOrigin: true,
                    rewrite: function (path) { return path.replace(/^\/api/, ''); },
                },
                '^/upload': {
                    target: 'http://127.0.0.1:19878/v1/upload',
                    changeOrigin: true,
                    rewrite: function (path) { return path.replace(new RegExp("^/upload"), ''); },
                },
            },
            // 提前转换和缓存文件以进行预热。可以在服务器启动时提高初始页面加载速度，并防止转换瀑布。
            warmup: {
                // 请注意，只应该预热频繁使用的文件，以免在启动时过载 Vite 开发服务器
                // 可以通过运行 npx vite --debug transform 并检查日志来找到频繁使用的文件
                clientFiles: ['./index.html', './src/{components,api}/*'],
            }
        }
    };
});
