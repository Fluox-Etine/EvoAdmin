import {request, type RequestOptions} from '@/utils/request';

/** 获取文件 POST /upload/group/list */
export async function list(
    body: API.UploadFileListDto,
    options?: RequestOptions,
) {
    return request<API.UploadGroupItemInfo[]>('/upload/file/list', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {}),
    });
}


/** 文件上传 删除方法 POST /upload/file/deleted */
export async function deleted( body: API.QueryId, options?: RequestOptions) {
    return request<any>('/upload/file/deleted', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || { successMsg:'操作成功' }),
    });
}