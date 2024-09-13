import type { TableColumn } from '@/components/business/dynamic-table';
import { formatToDateTime } from '@/utils/dateUtil';

export type TableColumnItem = TableColumn<any>;
export const baseColumns: TableColumnItem[] = [
    {
        title: 'id',
        dataIndex: 'id',
        hideInSearch: true,
        width: 20,
    },
    {
        title: '分组名称',
        dataIndex: 'name',
        hideInSearch: false,
        width: 80,
    },
    {
        title: '排序(数字越小越靠前)',
        dataIndex: 'sort',
        hideInSearch: true,
        width: 30,
    },
    {
        title: '创建时间',
        dataIndex: 'created_at',
        hideInSearch: true,
        width: 30,
        customRender: ({ record }) => {
            return formatToDateTime(record.created_at);
        },
    },
    {
        title: '更新时间',
        dataIndex: 'updated_at',
        hideInSearch: true,
        width: 30,
        customRender: ({ record }) => {
            return formatToDateTime(record.updated_at);
        },
    },

];