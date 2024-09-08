import {Tag, Image, Progress} from 'ant-design-vue';
import type {TableColumn} from '@/components/business/dynamic-table';
import {formatSizeUnits} from "@/utils";
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";

export type FileItem = {
    file: File;
    uid: string;
    name: string;
    size: number;
    status: string;
    thumbUrl: string;
    percent: number;
    type: number
};

export enum UploadResultStatus {
    SUCCESS = 'success',
    ERROR = 'error',
    UPLOADING = 'uploading',
    HASH = 'hash',
    MERGE = 'merge',
}

export const fileListColumns: TableColumn<FileItem>[] = [
    {
        dataIndex: 'thumbUrl',
        title: '缩略图',
        width: 100,
        customRender: ({record}) => {
            if (record.type === FileTypeEnum.IMAGE) {
                const {thumbUrl} = record;
                return thumbUrl && <Image src={thumbUrl}/>;
            } else {
                return '不支持预览'
            }
        },
    },
    {
        dataIndex: 'name',
        title: '文件名',
        align: 'left',
        customRender: ({text, record}) => {
            const {percent, status: uploadStatus} = record || {};
            let status: 'normal' | 'exception' | 'active' | 'success' = 'normal';
            if (uploadStatus === UploadResultStatus.ERROR) {
                status = 'exception';
            } else if (uploadStatus === UploadResultStatus.UPLOADING) {
                status = 'active';
            } else if (uploadStatus === UploadResultStatus.SUCCESS) {
                status = 'success';
            }
            return (
                <div>
                    <p class="truncate mb-1 max-w-[280px]" title={text}>
                        {text}
                    </p>
                    <Progress percent={percent} size="small" status={status}/>
                </div>
            );
        },
    },
    {
        dataIndex: 'size',
        title: '文件大小',
        width: 100,
        customRender: ({text = 0}) => {
            return formatSizeUnits(text);
        },
    },
    {
        dataIndex: 'status',
        dataIndex: 'status',
        title: '状态',
        width: 100,
        customRender: ({text}) => {
            if (text === UploadResultStatus.SUCCESS) {
                return <Tag color="green">上传成功</Tag>;
            } else if (text === UploadResultStatus.ERROR) {
                return <Tag color="red">上传失败</Tag>;
            } else if (text === UploadResultStatus.HASH) {
                return <Tag color="blue">计算文件哈希值中</Tag>;
            } else if (text === UploadResultStatus.UPLOADING) {
                return <Tag color="blue">上传中</Tag>;
            } else if (text === UploadResultStatus.MERGE) {
                return <Tag color="blue">文件合并中</Tag>;
            }
            return text || '待上传';
        },
    },
];
