import {ref} from 'vue';
import {defineStore} from 'pinia';
import type {RouteRecordRaw} from 'vue-router';
import * as Api from '@/api/backend/auth';
import {resetRouter} from '@/router';
import {generateDynamicRoutes} from '@/router/helper/routeHelper';

export const useUserStore = defineStore(
    'user',
    () => {
        const token = ref<string>();
        const perms = ref<string[]>([]);
        const menus = ref<RouteRecordRaw[]>([]);
        const userInfo = ref<Partial<API.UserEntity>>({});

        const sortMenus = (menus: RouteRecordRaw[] = []) => {
            return menus
                .filter((n) => {
                    const flag = !n.meta?.hideInMenu;
                    if (flag && n.children?.length) {
                        n.children = sortMenus(n.children);
                    }
                    return flag;
                })
                .sort((a, b) => ~~Number(a.meta?.orderNo) - ~~Number(b.meta?.orderNo));
        };

        /** 清空登录态(token、userInfo...) */
        const clearLoginStatus = () => {
            token.value = '';
            perms.value = [];
            menus.value = [];
            userInfo.value = {};
            resetRouter();
            setTimeout(() => {
                localStorage.clear();
            });
        };
        /** 登录成功保存token */
        const setToken = (_token: string) => {
            token.value = _token;
        };
        /** 登录 */
        const login = async (params: API.LoginDto) => {
            try {
                const data = await Api.authLogin(params);
                setToken(data.token);
                await afterLogin();
            } catch (error) {
                return Promise.reject(error);
            }
        };
        /** 登录成功之后, 获取用户信息以及生成权限路由 */
        const afterLogin = async () => {
            try {
                userInfo.value = await Api.accountProfile();
                await fetchPermsAndMenus();
            } catch (error) {
                return Promise.reject(error);
                // return logout();
            }
        };
        /** 获取权限及菜单 */
        const fetchPermsAndMenus = async () => {
            const [menusData, permsData] = await Promise.all([Api.accountMenu(), Api.accountPermissions()]);
            perms.value = permsData;
            const result = generateDynamicRoutes(menusData as unknown as RouteRecordRaw[]);
            menus.value = sortMenus(result);
        };
        /** 登出 */
        const logout = async () => {
            await Api.account.accountLogout();
            clearLoginStatus();
        };

        return {
            token,
            perms,
            menus,
            userInfo,
            login,
            afterLogin,
            logout,
            clearLoginStatus,
            fetchPermsAndMenus,
        };
    },
    {
        persist: {
            paths: ['token'],
        },
    },
);
