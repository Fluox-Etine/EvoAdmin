import {createApp} from 'vue'
import App from './App.vue'
import { setupRouter } from './router';

import {setupStore} from '@/store';

const app = createApp(App)

// 挂载vuex状态管理
setupStore(app);
// 挂载路由
await setupRouter(app);

app.mount('#app')
