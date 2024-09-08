import type {RouteRecordRaw} from 'vue-router';

const moduleName = 'demos';

const routes: Array<RouteRecordRaw> = [
    {
        path: '/demos',
        name: moduleName,
        redirect: {name: `${moduleName}-upload-file`},
        meta: {
            title: '官方示例',
            icon: 'ant-design:desktop-outlined',
        },
        children: [
            {
                path: 'upload-file',
                name: `${moduleName}-upload-file`,
                meta: {
                    title: '文件上传',
                    icon: 'ant-design:file-text-outlined',
                },
                component: () => import('@/views/demos/upload/index.vue')
            },
            {
                path: 'editor',
                name: `${moduleName}-editor`,
                meta: {
                    title: '富文本',
                    icon: 'ant-design:file-text-outlined',
                },
                component: () => import('@/views/demos/editor/index.vue')
            }
        ]
    }
]
export default routes;
