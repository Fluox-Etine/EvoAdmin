import {createApp} from 'vue'
import App from './App.vue'
import 'highlight.js/styles/a11y-dark.css'
import {setupRouter} from './router';
import {setupStore} from '@/store';
import {setupIcons} from "@/components/core/icon";
import hljsVuePlugin from '@highlightjs/vue-plugin'
import hljs from 'highlight.js/lib/core'
import php from 'highlight.js/lib/languages/php'
import {setupAntd, setupAssets, setupGlobalMethods} from '@/plugins';

const app = createApp(App)


hljs.registerLanguage('php', php)
function setupPlugins() {
    // 安装图标
    setupIcons();
    // 注册全局常用的ant-design-vue组件
    setupAntd(app);
    // 引入静态资源
    setupAssets();
    // 注册全局方法，如：app.config.globalProperties.$message = message
    setupGlobalMethods(app);
}

// 挂载vuex状态管理
setupStore(app);
// 挂载路由
await setupRouter(app);
setupPlugins();

app.use(hljsVuePlugin).mount('#app');
