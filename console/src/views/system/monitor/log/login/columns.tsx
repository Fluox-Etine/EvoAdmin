import type {TableColumn} from '@/components/business/dynamic-table';
import {formatToDateTime} from '@/utils/dateUtil';
import {Tag} from "ant-design-vue";

export type TableColumnItem = TableColumn<any>;
export const getStatusColor = (type) => {
    switch (type) {
        case 10:
            return '#1fa1ef';
        case 20:
            return '#b63535';
        case 30:
            return '#faad14';
    }
};
export const getStatusInfo = (type) => {
    switch (type) {
        case 10:
            return '登录成功';
        case 20:
            return '账户错误';
        case 30:
            return '密码错误';
    }
};
export const baseColumns: TableColumnItem[] = [
    {
        title: 'id',
        dataIndex: 'id',
        hideInSearch: true,
    },
    {
        title: '账户名',
        dataIndex: 'username',
        hideInSearch: false,
    },
    {
        title: 'ip',
        dataIndex: 'ip',
        hideInSearch: false,
    },
    {
        title: '浏览器',
        dataIndex: 'user_agent',
        hideInSearch: true,
    },
    {
        title: '登录时间',
        dataIndex: 'updated_at',
        hideInSearch: true,
        customRender: ({record}) => {
            return formatToDateTime(record.updated_at);
        },
    },
    {
        title: '登录状态',
        dataIndex: 'status',
        hideInSearch: false,
        formItemProps: {
            component: 'Select',
            componentProps: {
                options: [
                    {
                        label: '登录成功',
                        value: 10,
                    },
                    {
                        label: '账户错误',
                        value: 20,
                    },
                    {
                        label: '密码错误',
                        value: 30,
                    }
                ],
            },
        },
        customRender: ({record}) => {
            return <Tag color={getStatusColor(record.status)}>{getStatusInfo(record.status)}</Tag>
        }
    },
    {
        title: '登录id',
        dataIndex: 'uid',
        hideInSearch: false,
    },

];