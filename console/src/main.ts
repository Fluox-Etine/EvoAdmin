import {createApp} from 'vue'
import Antd from 'ant-design-vue';
// @ts-ignore
import App from './App.vue'
import { setupRouter } from './router';
import {setupStore} from '@/store';
import 'ant-design-vue/dist/reset.css';

const app = createApp(App)

// 挂载vuex状态管理
setupStore(app);
// 挂载路由
await setupRouter(app);

app.use(Antd).mount('#app')
