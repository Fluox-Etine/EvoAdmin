import {request, type RequestOptions} from '@/utils/request';

export async function list(options?: RequestOptions) {
    return request<any>('/gen/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        data: body,
        ...(options || {successMsg: '生成成功'}),
    });
}

