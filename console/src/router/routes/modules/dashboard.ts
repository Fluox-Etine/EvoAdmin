import type {RouteRecordRaw} from 'vue-router';

const routes: Array<RouteRecordRaw> = [
    {
        path: '/dashboard',
        name: 'dashboard',
        redirect: '/dashboard/index',
        meta: {
            title: '控制台',
            icon: 'ant-design:dashboard-outlined',
        },
        children: [
            {
                path: 'index',
                name: `dashboard-welcome`,
                meta: {
                    title: '仪表台',
                    icon: 'ant-design:home-filled',
                },
                component: () => import('@/views/dashboard/index.vue'),
            },
            // {
            //     path: '/demos-table',
            //     name: `demos-table`,
            //     meta: {
            //         title: '数据表格',
            //         icon: 'ant-design:desktop-outlined',
            //     },
            //     component: () => import('@/views/dashboard/demos/table.vue'),
            // }
        ],
    }
];

export default routes;
