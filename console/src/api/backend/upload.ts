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


/** 上传分片 POST /api/upload/chunk */
export async function uploadChunk(body: API.UploadChunk, options?: RequestOptions) {
    const formData = new FormData();
    formData.append('chunk', body.chunk)
    formData.append('hash', body.hash)
    formData.append('index', body.index)
    formData.append('fileName', body.fileName)

    return request<any>('/common/uploadChunk', {
        method: 'POST',
        data: formData,
        requestType: 'form',
        ...(options || {}),
    });
}

/** 合并分片 POST /api/upload/chunk/merge */
export async function chunkMerge(body: API.UploadChunkMerge, options?: RequestOptions) {
    return request<any>('/common/chunkMerge', {
        method: 'POST',
        data: body,
        ...(options || {}),
    });
}