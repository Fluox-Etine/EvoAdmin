import type {FormSchema} from '@/components/business/schema-form/';
import * as Api from "@/api/backend/gen.ts"

export const formSchemas: FormSchema<any>[] = [
    {
        field: 'name',
        component: 'Input',
        label: '字典名称',
        required: true,
        colProps: {
            span: 24,
        },
    },
    {
        field: 'table',
        component: 'Select',
        label: '数据表',
        required: true,
        colProps: {
            span: 24,
        },
        componentProps: ({formModel, formInstance}) => {
            return {
                request: async () => {
                    const {data} = await Api.tableSheet();
                    return data.map(item => {
                        return {
                            label: item.comment + '（' + item.name + '）',
                            value: item.name,
                        }
                    })
                },
                onChange: async (e: any) => {
                    const {fields} = await Api.tableDetail({tableName: e})
                    const field = fields.map(item => {
                        return {
                            label: item.COLUMN_COMMENT + '（' + item.COLUMN_NAME + '）',
                            value: item.COLUMN_NAME,
                        }
                    })
                    const {updateSchema} = formInstance;
                    formModel.label_field = undefined
                    formModel.value_field = undefined
                    updateSchema({
                        field: 'label_field',
                        componentProps: {
                            options: field,
                        },
                    });
                    updateSchema({
                        field: 'value_field',
                        componentProps: {
                            options: field,
                        },
                    })
                },
            }
        },
    },
    {
        field: 'dict_type',
        component: 'RadioGroup',
        label: '字典类型',
        defaultValue: 1,
        required: true,
        colProps: {
            span: 24,
        },
        componentProps: {
            options: [
                {
                    label: '下列列表',
                    value: 1,
                },
                {
                    label: '树形结构',
                    value: 2,
                },
            ],
        },
    },
    {
        field: 'label_field',
        component: 'Select',
        label: 'label字段',
        required: true,
        helpMessage: ['数据表字段会被强行转为label'],
        colProps: {
            span: 24,
        },
        componentProps: {
            options: []
        },
    },
    {
        field: 'value_field',
        component: 'Select',
        label: 'value字段',
        required: true,
        helpMessage: ['数据表字段会被强行转为value'],
        colProps: {
            span: 24,
        },
        componentProps: {
            options: []
        },
    },
    {
        field: 'cache_type',
        component: 'RadioGroup',
        label: '缓存数据',
        helpMessage: ['开启后，数据会优先查询缓存，如果没有，则查询数据库'],
        required: true,
        mode: 'multiple',
        defaultValue: 2,
        colProps: {
            span: 24,
        },
        componentProps: {
            options: [
                {
                    label: '开启',
                    value: 1,
                },
                {
                    label: '关闭',
                    value: 2,
                },
            ],
        },
    },
    {
        field: 'cache_enhance',
        component: 'RadioGroup',
        label: '缓存增强',
        defaultValue: 1,
        helpMessage: ['开启后，查询优先级分别为：静态数组、Redis缓存、数据库', '关闭后，查询优先级分别为：Redis缓存、数据库'],
        vShow: ({formModel}) => {
            return formModel.cache_type === 1;
        },
        colProps: {
            span: 24,
        },
        componentProps: {
            options: [
                {
                    label: '开启',
                    value: 1,
                },
                {
                    label: '关闭',
                    value: 2,
                },
            ],
        },
    },
    {
        field: 'cache_detail',
        component: 'RadioGroup',
        label: '缓存详情',
        defaultValue: 1,
        vShow: ({formModel}) => {
            return formModel.cache_type === 1;
        },
        colProps: {
            span: 24,
        },
        componentProps: {
            options: [
                {
                    label: '开启',
                    value: 1,
                },
                {
                    label: '关闭',
                    value: 2,
                },
            ],
        },
    }
];