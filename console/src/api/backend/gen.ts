import {request, type RequestOptions} from '@/utils/request';

/** 获取数据表列表 GET /gen/table/sheet */
export async function tableList(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    params: any,
    options?: RequestOptions,
) {
    return request<{
        meta?: API.Meta;
    }>('/gen/table/sheet', {
        method: 'GET',
        params: {
            ...params,
        },
        ...(options || {}),
    });
}


/** 获取数据表详情 GET /gen/table/sheet/detail */
export async function tableDetail(
    // 叠加生成的Param类型 (非body参数swagger默认没有生成对象)
    params: any,
    options?: RequestOptions,
) {
    return request<{
    }>('/gen/table/sheet/detail', {
        method: 'GET',
        params: {
            ...params,
        },
        ...(options || {}),
    });
}

/** 生成代码 POST /gen/generate */
export async function gen(body: any, options?: RequestOptions) {
    return request<any>('/gen/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {successMsg: '生成成功'}),
    });
}