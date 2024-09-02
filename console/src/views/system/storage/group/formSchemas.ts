import type { FormSchema } from '@/components/business/schema-form/';

export const formSchemas: FormSchema<any>[] = [
    {
        field: 'name',
        component: 'Input',
        label: '分组名称',
        rules: [{ required: true }],
        colProps: {
            span: 12,
        },
    },
    {
        field: 'sort',
        component: 'Input',
        label: '排序(数字越小越靠前)',
        rules: [{ required: true }],
        colProps: {
            span: 12,
        },
    },

];