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
}
