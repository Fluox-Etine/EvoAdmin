/**
 * 从后端接口获取：/api/system/menus/permissions
 * @description 权限列表, 仅供开发时提供 ts 类型提示，无实际作用
 */
import * as Api from '@/api/backend/menu';

const permissions = Api.menuGetPermissions()
export type PermissionType = (typeof permissions)[];

console.log('permissionsCode', permissions);
