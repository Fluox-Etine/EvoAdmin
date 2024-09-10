import {request, type RequestOptions} from '@/utils/request';

/** 登录日志 列表方法 POST /system/login/log/list */
export async function list(body: any, options?: RequestOptions) {
    return request<any>('/system/log/login/list', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {}),
    });
}
