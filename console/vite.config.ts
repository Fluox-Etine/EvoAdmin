import vue from '@vitejs/plugin-vue'
import {resolve} from "path";
import Unocss from 'unocss/vite';
import {defineConfig} from 'vite'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        vue(),
        Unocss(),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, './src'),
        }
    }
})
