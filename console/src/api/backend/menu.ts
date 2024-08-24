import {request, type RequestOptions} from '@/utils/request';

/** 获取所有菜单列表 GET /system/menus/list */
export async function menuList(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    params: API.MenuListParams,
    options?: RequestOptions,
) {
    return request<API.MenuItemInfo[]>('/system/menu/list', {
        method: 'GET',
        params: {
            ...params,
        },
        ...(options || {}),
    });
}

/** 新增菜单或权限 POST /system/menu */
export async function menuCreate(body: API.MenuDto, options?: RequestOptions) {
    return request<any>('/system/menu/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {successMsg: '创建成功'}),
    });
}

/** 更新菜单或权限 POST /system/menu/update */
export async function menuUpdate(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    body: API.MenuUpdateDto,
    options?: RequestOptions,
) {
    return request<any>(`/system/menu/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {successMsg: '更新成功'}),
    });
}

/** 删除菜单或权限 GET /system/menu/delete */
export async function menuDelete(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    params: API.QueryId,
    options?: RequestOptions,
) {
    return request<any>(`/system/menu/delete`, {
        method: 'GET',
        params: {...params},
        ...(options || {successMsg: '删除成功'}),
    });
}

/** 获取后端定义的所有权限集 GET /system/menu/permissions */
export async function menuGetPermissions(options?: RequestOptions) {
    return request<string[]>('/system/menu/permissions', {
        method: 'GET',
        ...(options || {}),
    });
}