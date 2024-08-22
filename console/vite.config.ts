import {fileURLToPath, URL} from 'node:url'

import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import unocss from 'unocss/vite';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        vue(),
        vueJsx(),
        unocss()
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url))
        }
    },
    server: {
        host: '0.0.0.0',
        port: 8088,
        open: true,
        proxy: {
            '^/api': {
                target: 'http://127.0.0.1:19878/v1/console',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, ''),
            },
            '^/upload': {
                target: 'https://nest-api.buqiyuan.site/upload',
                // target: 'http://127.0.0.1:7001/upload',
                changeOrigin: true,
                rewrite: (path) => path.replace(new RegExp(`^/upload`), ''),
            },
        },
        // 提前转换和缓存文件以进行预热。可以在服务器启动时提高初始页面加载速度，并防止转换瀑布。
        warmup: {
            // 请注意，只应该预热频繁使用的文件，以免在启动时过载 Vite 开发服务器
            // 可以通过运行 npx vite --debug transform 并检查日志来找到频繁使用的文件
            clientFiles: ['./index.html', './src/{components,api}/*'],
        },
    },
})
