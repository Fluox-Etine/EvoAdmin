import {request, type RequestOptions} from '@/utils/request';

/** 获取文件 POST /upload/group/list */
export async function list(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    body: API.UploadFileListParams,
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


/** 删除分组 POST /system/menu/delete */
export async function deleted(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    body: API.QueryId,
    options?: RequestOptions,
) {
    return request<any>(`/upload/file/delete`, {
        method: 'POST',
        data: body,
        ...(options || {successMsg: '删除成功'}),
    });
}