// eslint-disable-next-line @typescript-eslint/no-unused-vars
declare namespace API {
    // 登录
    type LoginDto = {
        /** 账户名 */
        username: string;
        /** 密码 */
        password: string;
    }

    // token
    type LoginToken = {
        /** JWT身份Token */
        token: string;
    };

    // 用户详情
    type UserEntity = {
        id: number;
        username: string;
        created_at: string;
        updated_at: string;
    }

    // 菜单
    type AccountMenus = {
        meta: MenuMeta;
        id: number;
        path: string;
        name: string;
        component: string;
    };

    type MenuMeta = {
        title: string;
        permission?: string;
        type?: number;
        icon?: string;
        orderNo?: number;
        component?: string;
        isExt?: boolean;
        extOpenMode?: number;
        keepAlive?: number;
        show?: number;
        activeMenu?: string;
        status?: number;
    };
}