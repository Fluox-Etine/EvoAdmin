import type { FormSchema } from '@/components/business/schema-form/';

export const formSchemas: FormSchema<any>[] = [
    {
        field: 'name',
        component: 'Input',
        label: '分组名称',
        rules: [{ required: true }],
        colProps: {
            span: 24,
        },
    },
    {
        field: 'sort',
        component: 'InputNumber',
        label: '排序',
        rules: [{ required: true }],
        colProps: {
            span: 24,
        },
    },

];