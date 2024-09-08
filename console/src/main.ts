import 'core-js/actual/promise/with-resolvers';
import {createApp} from 'vue'
import App from './App.vue'
import {setupIcons} from "@/components/core/icon";
import {setupRouter} from './router';
import {setupStore} from '@/store';
import {setupAntd, setupAssets, setupGlobalMethods, setupHighlight, setupBoot} from '@/plugins';

const app = createApp(App)


function setupPlugins() {
    // 安装图标
    setupIcons();
    // 注册全局常用的ant-design-vue组件
    setupAntd(app);
    // 引入静态资源
    setupAssets();
    // 注册全局方法，如：app.config.globalProperties.$message = message
    setupGlobalMethods(app);
    // 注册语法语法高亮
    setupHighlight(app)
    // 注册富文本编辑器插件
    setupBoot()
}

async function setupApp() {
    // 挂载vuex状态管理
    setupStore(app);
    // 挂载路由
    await setupRouter(app);

    app.mount('#app');
}

setupPlugins();

setupApp();

