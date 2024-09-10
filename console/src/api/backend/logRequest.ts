import {request, type RequestOptions} from '@/utils/request';

/** 登录日志 列表方法 POST /sys/log/login/list */
export async function list(body: any, options?: RequestOptions) {
    return request<any>('/system/log/request/list', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {}),
    });
}


/** 登录日志 详情方法 POST /sys/log/login/detail */
export async function detail(body: any, options?: RequestOptions) {
    return request<any>('/system/log/request/detail', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options),
    });
}