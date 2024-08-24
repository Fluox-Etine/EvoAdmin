// eslint-disable-next-line @typescript-eslint/no-unused-vars
declare namespace API {

    type RoleListParams = {
        page?: number;
        pageSize?: number;
        field?: string;
        /** 角色名称 */
        name?: string;
        /** 角色值 */
        value?: string;
        /** 角色备注 */
        remark?: string;
    };


    type RoleEntity = {
        /** 角色名 */
        name: string;
        /** 角色标识 */
        value: string;
        /** 角色描述 */
        remark: string;
        /** 状态：1启用，0禁用 */
        status: number;
        /** 是否默认用户 */
        default: boolean;
        id: number;
        createdAt: string;
        updatedAt: string;
    };

    type RoleDto = {
        /** 角色名称 */
        name: string;
        /** 角色标识 */
        value: string;
        /** 角色备注 */
        remark?: string;
        /** 状态 */
        status: 0 | 1;
        /** 关联菜单、权限编号 */
        menuIds?: number[];
    };

    type RoleUpdateDto = {
        id: number;
        /** 角色名称 */
        name?: string;
        /** 角色标识 */
        value?: string;
        /** 角色备注 */
        remark?: string;
        /** 状态 */
        status?: 0 | 1;
        /** 关联菜单、权限编号 */
        menuIds?: number[];
    };

}