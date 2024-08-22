// @ts-ignore
/* eslint-disable */

/**
 * 该文件为 @umijs/openapi 插件自动生成，请勿随意修改。如需修改请通过配置 openapi.config.ts 进行定制化。
 * */

import {request, type RequestOptions} from '@/utils/request';

/** 登录 POST /auth/login */
export async function authLogin(body: API.LoginDto, options?: RequestOptions) {
    return request<API.LoginToken>('/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {}),
    });
}

/** 账户登出 POST /account/logout */
export async function accountLogout(options?: RequestOptions) {
    return request<any>('/account/logout', {
        method: 'POST',
        ...(options || {}),
    });
}


/** 获取账户资料 GET /api/account/profile */
export async function accountProfile(options?: RequestOptions) {
    return request<API.AccountInfo>('/account/profile', {
        method: 'GET',
        ...(options || {}),
    });
}
/** 获取菜单列表 POST /account/menus */
export async function accountMenu(options?: RequestOptions) {
    return request<API.AccountMenus[]>('/account/menus', {
        method: 'POST',
        ...(options || {}),
    });
}

/** 获取权限列表 POST /account/permissions */
export async function accountPermissions(options?: RequestOptions) {
    return request<string[]>('/account/permissions', {
        method: 'POST',
        ...(options || {}),
    });
}


