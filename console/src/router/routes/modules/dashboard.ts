import type { RouteRecordRaw } from 'vue-router';

const moduleName = 'dashboard';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/dashboard',
    name: moduleName,
    redirect: '/dashboard/welcome',
    meta: {
      title: '控制台',
      icon: 'ant-design:dashboard-outlined',
    },
    children: [
      {
        path: 'welcome',
        name: `${moduleName}-welcome`,
        meta: {
          title: '仪表台',
          icon: 'ant-design:home-filled',
        },
        component: () => import('@/views/dashboard/welcome/index.vue'),
      },
    ],
  },
];

export default routes;
