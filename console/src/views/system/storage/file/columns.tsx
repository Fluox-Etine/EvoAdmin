import type {TableColumn} from '@/components/business/dynamic-table';
import {formatToDateTime} from '@/utils/dateUtil';
import {Image, Tag} from 'ant-design-vue';
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";
import * as GroupApi from '@/api/backend/uploadGroup.ts'
import {ref} from "vue";

export const domain = import.meta.env.VITE_DOMAIN_URL;

export const getFileTypeColor = (status) => {
    switch (status) {
        case 10:
            return '#b63535';
        case 20:
            return '#52c41a';
        case 30:
            return '#faad14';
    }
};
export const getFileTypeInfo = (status) => {
    switch (status) {
        case 10:
            return '图片';
        case 20:
            return '视频';
        case 30:
            return '文件';
    }
};
const group = ref([]);
GroupApi.selectGroupList().then(res => {
    group.value = res
})
const getGroupName = (id) => {
    // 使用 findIndex 而不是 find，因为 find 返回的是第一个匹配项，而 findIndex 返回的是索引
    const index = group.value.findIndex(item => item.value === id);
    // 如果找到了对应的项，则返回标签名，否则返回 undefined
    return index !== -1 ? group.value[index].label : undefined;
}
export type TableColumnItem = TableColumn<any>;
export const baseColumns: TableColumnItem[] = [
    {
        title: 'id',
        dataIndex: 'id',
        width: 80,
        hideInSearch: true,
    },
    {
        title: '文件名称',
        dataIndex: 'file_name',
        hideInSearch: false,
        width: 150
    },
    {
        title: '图片预览',
        dataIndex: 'file_path',
        hideInSearch: false,
        width: 150,
        customRender({record}) {
            if (record.file_type === FileTypeEnum.IMAGE) {
                return <Image src={domain + record.file_path}></Image>;
            } else {
                return '不支持预览'
            }
        },
    },
    {
        title: '上传来源',
        dataIndex: 'channel',
        hideInSearch: false,
        width: 100,
        formItemProps: {
            component: 'Select',
            componentProps: {
                options: [
                    {
                        label: '管理端',
                        value: 10,
                    },
                    {
                        label: '用户端',
                        value: 20,
                    },
                ],
            },
        },
        customRender: ({record}) => {
            const isEnable = ~~record.channel === 20;
            return <Tag color={isEnable ? 'success' : 'red'}>{isEnable ? '管理端' : '用户端'}</Tag>;
        },
    },
    {
        title: '文件分组',
        dataIndex: 'group_id',
        hideInSearch: true,
        width: 150,
        customRender: ({record}) => {
            return <Tag color="cyan">{getGroupName(record.group_id)}</Tag>
        },
    },
    {
        title: '文件大小',
        dataIndex: 'file_size',
        hideInSearch: true,
        width: 100,
        customRender: ({text = 0}) => {
            return text && `${(text / 1024).toFixed(2)}KB`;
        },
    },
    {
        title: '文件类型',
        dataIndex: 'file_type',
        hideInSearch: true,
        width: 100,
        formItemProps: {
            component: 'Select',
            componentProps: {
                options: [
                    {
                        label: '图片',
                        value: 10,
                    },
                    {
                        label: '视频',
                        value: 20,
                    },
                    {
                        label: '文件',
                        value: 30,
                    },
                ],
            },
        },
        customRender: ({record}) => {
            return <Tag color={getFileTypeColor(record.file_type)}>{getFileTypeInfo(record.file_type)}</Tag>
        }
    },
    {
        title: '上传者id',
        dataIndex: 'uploader_id',
        hideInSearch: false,
        width: 80,
    },
    {
        title: '创建时间',
        dataIndex: 'created_at',
        hideInSearch: true,
        width: 150,
        customRender: ({record}) => {
            return formatToDateTime(record.created_at);
        },
    },
];