// eslint-disable-next-line @typescript-eslint/no-unused-vars
declare namespace API {

    type FileUploadDto = {
        /** 文件 */
        file: Record<string, any>;
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
    }
}