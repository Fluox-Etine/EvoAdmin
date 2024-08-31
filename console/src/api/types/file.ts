// eslint-disable-next-line @typescript-eslint/no-unused-vars
declare namespace API {

    type FileUploadDto = {
        /** 文件 */
        file: Record<string, any>;
        /** 文件组 */
        group: number;
        /** 文件类型 */
        type: number;
        /** 文件名 */
        fileName: string;
    };

    type UploadChunk = {
        /** 切片文件 */
        chunk: Record<string, any>;
        /** hash值 */
        hash: number;
        /** 文件索引 */
        index: number;
        /** 文件名 */
        fileName: string;
    }

    type UploadChunkMerge = {
        /** 文件hash */
        hash: string;
        /** 文件组 */
        group: number;
        /** 文件类型 */
        type: number;
        /** 文件大小 */
        size: number;
    }
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

    /** 列表参数 */
    type UploadFileListDto = {
        /** 文件分组 */
        group_id?: number;
        /** 上传来源(10管理端 20用户端) */
        channel?: number;
        /** 文件类型(10图片 20附件 30视频) */
        file_type?: number;
        /** 文件名称 */
        file_name?: string;
    };
}