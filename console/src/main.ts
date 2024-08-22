import {createApp} from 'vue'
import Antd from 'ant-design-vue';
import App from './App.vue'
import {setupRouter} from './router';
import {setupStore} from '@/store';
import 'ant-design-vue/dist/reset.css';
import {setupAssets} from '@/plugins';

const app = createApp(App)

function setupPlugins() {
    // 引入静态资源
    setupAssets(app);
}

// 挂载vuex状态管理
setupStore(app);
// 挂载路由
await setupRouter(app);
setupPlugins();
app.use(Antd).mount('#app')
