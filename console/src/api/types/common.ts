// @ts-ignore
/* eslint-disable */
declare namespace API {
    type ResOp = {
        data: Record<string, any>;
        code: number;
        message: string;
    };

    /** 全局 id 条件 */
    type QueryId = {
        id: number;
    }

    /** 全局分页参数信息 */
    type Meta = {
        itemCount?: number;
        totalItems?: number;
        itemsPerPage?: number;
        totalPages?: number;
        currentPage?: number;
    }

    /** 全局通过表格查询返回结果 */
    type TableListResult<T = any> = {
        items?: T;
        meta?: PaginationResult;
    };

    /** 全局通用表格分页返回数据结构 */
    type PaginationResult = {
        itemCount?: number;
        totalItems?: number;
        itemsPerPage?: number;
        totalPages?: number;
        currentPage?: number;
    };

    /** 全局通用表格分页请求参数 */
    type PageParams<T = any> = {
        page?: number;
        pageSize?: number;
    } & {
        [P in keyof T]?: T[P];
    };
}
