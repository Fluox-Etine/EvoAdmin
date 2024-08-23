// eslint-disable-next-line @typescript-eslint/no-unused-vars
declare namespace API {

    type MenuListParams = {
        /** 菜单或权限名称 */
        name?: string;
        /** 前端路由地址 */
        path?: string;
        /** 菜单路由路径或外链 */
        component?: string;
    };


    type MenuItemInfo = {
        id: number;
        parent_id: null;
        path: string;
        name: string;
        permission: string;
        type: number;
        icon: string;
        order_no: number;
        component: string;
        keep_alive: number;
        show: number;
        status: number;
        is_ext: number;
        ext_open_mode: number;
        active_menu: null;
        created_at: string;
        updated_at: string;
        children: MenuItemInfo[]
        pid: number;
    };

    type MenuDto = {
        id: number;
        /** 菜单类型 */
        type: 0 | 1 | 2;
        /** 父级菜单 */
        parentId: number;
        /** 菜单或权限名称 */
        name: string;
        /** 排序 */
        orderNo: number;
        /** 前端路由地址 */
        path: string;
        /** 是否外链 */
        isExt: boolean;
        /** 外链打开方式 */
        extOpenMode: 1 | 2;
        /** 菜单是否显示 */
        show: 0 | 1;
        /** 设置当前路由高亮的菜单项，一般用于详情页 */
        activeMenu?: string;
        /** 是否开启页面缓存 */
        keepAlive: 0 | 1;
        /** 状态 */
        status: 0 | 1;
        /** 菜单图标 */
        icon?: string;
        /** 对应权限 */
        permission: string;
        /** 菜单路由路径或外链 */
        component?: string;
    };

    type MenuUpdateDto = {
        /** 菜单类型 */
        type?: 0 | 1 | 2;
        /** 父级菜单 */
        parentId?: number;
        /** 菜单或权限名称 */
        name?: string;
        /** 排序 */
        orderNo?: number;
        /** 前端路由地址 */
        path?: string;
        /** 是否外链 */
        isExt?: boolean;
        /** 外链打开方式 */
        extOpenMode?: 1 | 2;
        /** 菜单是否显示 */
        show?: 0 | 1;
        /** 设置当前路由高亮的菜单项，一般用于详情页 */
        activeMenu?: string;
        /** 是否开启页面缓存 */
        keepAlive?: 0 | 1;
        /** 状态 */
        status?: 0 | 1;
        /** 菜单图标 */
        icon?: string;
        /** 对应权限 */
        permission?: string;
        /** 菜单路由路径或外链 */
        component?: string;
    };
}