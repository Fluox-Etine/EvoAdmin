import {request, type RequestOptions} from '@/utils/request';

/** 获取角色列表 GET /system/role/list */
export async function roleList(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    params: API.RoleListParams,
    options?: RequestOptions,
) {
    return request<{
        items?: API.RoleEntity[];
        meta?: API.Meta;
    }>('/system/role/list', {
        method: 'GET',
        params: {
            ...params,
        },
        ...(options || {}),
    });
}


/** 新增角色 POST /system/role */
export async function roleCreate(body: API.RoleDto, options?: RequestOptions) {
    return request<any>('/system/role/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {successMsg: '创建成功'}),
    });
}

/** 获取角色信息 GET /system/role/detail */
export async function roleInfo(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    params: API.QueryId,
    options?: RequestOptions,
) {
    return request<any>(`/system/role/detail`, {
        method: 'GET',
        params: {...params},
        ...(options || {}),
    });
}

/** 更新角色 POST /system/role/update */
export async function roleUpdate(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    body: API.RoleUpdateDto,
    options?: RequestOptions,
) {
    return request<any>(`/system/role/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {successMsg: '更新成功'}),
    });
}


/** 删除角色 DELETE /system/role/delete */
export async function roleDelete(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    params: API.QueryId,
    options?: RequestOptions,
) {
    return request<any>(`/system/role/delete`, {
        method: 'get',
        params: {...params},
        ...(options || {successMsg: '删除成功'}),
    });
}