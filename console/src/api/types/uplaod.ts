// eslint-disable-next-line @typescript-eslint/no-unused-vars
declare namespace API {

    type FileUploadDto = {
        /** 文件 */
        file: Record<string, any>;
        /** 文件组 */
        group: number;
        /** 文件类型 */
        type: number;
        /** 上传渠道 */
        channel: number;
    };

    type UploadGroupItemInfo = {
        id: number;
        name: string;
        sort: number;
        created_at: number;
    }

    type UploadGroupListParams = {
        name?: string;
    }

    type UploadGroupDto = {
        id?: number;
        name: string;
        sort: number;
    }

    type UploadFileListParams = {

    }
}