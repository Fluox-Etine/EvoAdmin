import type {TableColumn} from '@/components/business/dynamic-table';
import {formatToDateTime} from '@/utils/dateUtil';
import {Tag} from "ant-design-vue";

export type TableColumnItem = TableColumn<any>;
export const baseColumns: TableColumnItem[] = [
    {
        title: 'uuid',
        dataIndex: 'uuid',
        hideInSearch: false,
        width: 300,
    },
    {
        title: '操作人',
        dataIndex: 'uid',
        hideInSearch: false,
        width: 100,
    },
    {
        title: '请求接口',
        dataIndex: 'uri',
        hideInSearch: false,
    },
    {
        title: '请求方式',
        dataIndex: 'method',
        hideInSearch: true,
        width: 100,
    },
    {
        title: '执行时间(ms)',
        dataIndex: 'exec_time',
        hideInSearch: true,
        width: 100,
    },
    {
        title: '请求结果',
        dataIndex: 'status',
        hideInSearch: false,
        width: 100,
        customRender: ({record}) => {
            const status = record.status;
            const enable = ~~status === 10;
            const color = enable ? 'green' : 'red';
            const text = enable ? '成功' : '失败';
            return <Tag color={color}>{text}</Tag>;
        },
        formItemProps: {
            component: 'Select',
            componentProps: {
                options: [
                    {
                        label: '成功',
                        value: 10,
                    },
                    {
                        label: '失败',
                        value: 20,
                    },
                ],
            },
        },
    },
    {
        title: 'IP',
        dataIndex: 'ip',
        hideInSearch: true,
        width: 150,
    },
    {
        title: '携带信息',
        dataIndex: 'user_agent',
        hideInSearch: true,
    },
    {
        title: '进程id',
        dataIndex: 'pid',
        hideInSearch: true,
        width: 100,
    },
    {
        title: '请求时间',
        dataIndex: 'created_at',
        hideInSearch: false,
        width: 180,
        customRender: ({record}) => {
            return formatToDateTime(record.created_at);
        },
    }
];