import {request, type RequestOptions} from '@/utils/request';


/** 上传 POST /api/upload */
export async function uploadUpload(body: API.FileUploadDto, file?: File, options?: RequestOptions) {
    const formData = new FormData();

    if (file) {
        formData.append('file', file);
    }

    Object.keys(body).forEach((ele) => {
        const item = (body as any)[ele];

        if (item !== undefined && item !== null) {
            if (typeof item === 'object' && !(item instanceof File)) {
                if (item instanceof Array) {
                    item.forEach((f) => formData.append(ele, f || ''));
                } else {
                    formData.append(ele, JSON.stringify(item));
                }
            } else {
                formData.append(ele, item);
            }
        }
    });

    return request<any>('/common/upload', {
        method: 'POST',
        data: formData,
        requestType: 'form',
        ...(options || {}),
    });
}


/** 获取所有上传分组 GET /upload/group/list */
export async function list(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    body: API.UploadGroupListParams,
    options?: RequestOptions,
) {
    return request<API.UploadGroupItemInfo[]>('/upload/group/list', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {}),
    });
}


/** 添加分组 POST /upload/group/create  */
export async function create(body: API.UploadGroupDto, options?: RequestOptions) {
    return request<any>('/upload/group/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {successMsg: '创建成功'}),
    });
}

/** 编辑分组 POST /upload/group/update  */
export async function update(body: API.UploadGroupDto, options?: RequestOptions) {
    return request<any>('/upload/group/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {successMsg: '编辑成功'}),
    });
}


/** 编辑分组 POST /system/menu/delete */
export async function deleted(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    body: API.QueryId,
    options?: RequestOptions,
) {
    return request<any>(`/upload/group/delete`, {
        method: 'POST',
        data: body,
        ...(options || {successMsg: '删除成功'}),
    });
}