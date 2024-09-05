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
                    title: '上传文件',
                    icon: 'ant-design:file-text-outlined',
                },
                component: () => import('@/views/demos/upload/index.vue')
            }
        ]
    }
]
export default routes;
