import {request, type RequestOptions} from '@/utils/request';

/** 文件资源 列表方法 POST /upload/file/list */
export async function list(body: API.UploadFileListDto, options?: RequestOptions) {
    return request<any>('/upload/file/list', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {}),
    });
}

/** 文件资源 删除方法 POST /upload/file/deleted */
export async function deleted(body: API.QueryId, options?: RequestOptions) {
    return request<any>('/upload/file/deleted', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {successMsg: '操作成功'}),
    });
}

/** 文件资源 详情方法 POST /upload/file/detail */
export async function detail(body: API.QueryId, options?: RequestOptions) {
    return request<any>('/upload/file/detail', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options),
    });
}