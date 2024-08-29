import {Tag, Image, Progress} from 'ant-design-vue';
import type {TableColumn} from '@/components/core/dynamic-table';

export type FileItem = {
    file: File;
    uid: string;
    name: string;
    size: number;
    status: string;
    thumbUrl: string;
    percent: number;
};

export enum UploadResultStatus {
    SUCCESS = 'success',
    ERROR = 'error',
    UPLOADING = 'uploading',
}

export const fileListColumns: TableColumn<FileItem>[] = [
    {
        dataIndex: 'thumbUrl',
        title: '缩略图',
        width: 100,
        customRender: ({record}) => {
            const {thumbUrl} = record;
            return thumbUrl && <Image src={thumbUrl}/>;
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
            return text && `${(text / 1024).toFixed(2)}KB`;
        },
    },
    {
        dataIndex: 'status',
        title: '状态',
        width: 100,
        customRender: ({text}) => {
            if (text === UploadResultStatus.SUCCESS) {
                return <Tag color="green">上传成功</Tag>;
            } else if (text === UploadResultStatus.ERROR) {
                return <Tag color="red">上传失败</Tag>;
            } else if (text === UploadResultStatus.UPLOADING) {
                return <Tag color="blue">上传中</Tag>;
            }

            return text || '待上传';
        },
    },
];
