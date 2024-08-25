/*
 Navicat Premium Dump SQL
 Source Schema         : evo_php_admin

 Target Server Type    : MySQL
 Target Server Version : 80200 (8.2.0)
 File Encoding         : 65001

 Date: 25/08/2024 20:22:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for evo_sys_admin
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_admin`;
CREATE TABLE `evo_sys_admin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '账户',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '密码',
  `created_at` bigint DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint DEFAULT NULL COMMENT '更新时间',
  `deleted_at` bigint DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `username` (`username`) USING BTREE /*!80000 INVISIBLE */
) ENGINE=InnoDB AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC COMMENT='系统用户表';

-- ----------------------------
-- Records of evo_sys_admin
-- ----------------------------
BEGIN;
INSERT INTO `evo_sys_admin` (`id`, `username`, `password`, `created_at`, `updated_at`, `deleted_at`) VALUES (1, 'admin', '$2y$10$qGd7As4hCMJdwDU4KMMgV.U4W9JVSLvs35wZERKEwr8fiqzpX8Fpu', 1717655358, 1717655358, NULL);
COMMIT;

-- ----------------------------
-- Table structure for evo_sys_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_admin_role`;
CREATE TABLE `evo_sys_admin_role` (
  `admin_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`admin_id`,`role_id`) USING BTREE,
  KEY `IDX_96311d970191a044ec048011f4` (`admin_id`) USING BTREE,
  KEY `IDX_6d61c5b3f76a3419d93a421669` (`role_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='管理员角色';

-- ----------------------------
-- Records of evo_sys_admin_role
-- ----------------------------
BEGIN;
INSERT INTO `evo_sys_admin_role` (`admin_id`, `role_id`) VALUES (1, 1);
COMMIT;

-- ----------------------------
-- Table structure for evo_sys_menu
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_menu`;
CREATE TABLE `evo_sys_menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `permission` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT '0',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '',
  `order_no` int DEFAULT '0',
  `component` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keep_alive` tinyint NOT NULL DEFAULT '1',
  `show` tinyint NOT NULL DEFAULT '1',
  `status` tinyint NOT NULL DEFAULT '1',
  `is_ext` tinyint NOT NULL DEFAULT '0',
  `ext_open_mode` tinyint NOT NULL DEFAULT '1',
  `active_menu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` bigint DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='菜单表';

-- ----------------------------
-- Records of evo_sys_menu
-- ----------------------------
BEGIN;
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (1, NULL, '/system', '系统管理', '', 0, 'ant-design:setting-outlined', 255, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1724466472);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (3, 1, '/system/role', '角色管理', 'system:role:list', 1, 'ep:user', 1, 'system/role/index', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (4, 1, '/system/menu', '菜单管理', 'system:menu:list', 1, 'ep:menu', 2, 'system/menu/index', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (24, 3, '', '新增', 'system:role:create', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (25, 3, '', '删除', 'system:role:delete', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (26, 3, '', '修改', 'system:role:update', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (27, 3, '', '查询', 'system:role:read', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (28, 4, NULL, '新增', 'system:menu:create', 2, NULL, 0, NULL, 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (29, 4, NULL, '删除', 'system:menu:delete', 2, NULL, 0, NULL, 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (30, 4, '', '修改', 'system:menu:update', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` (`id`, `parent_id`, `path`, `name`, `permission`, `type`, `icon`, `order_no`, `component`, `keep_alive`, `show`, `status`, `is_ext`, `ext_open_mode`, `active_menu`, `created_at`, `updated_at`) VALUES (31, 4, NULL, '查询', 'system:menu:read', 2, NULL, 0, NULL, 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
COMMIT;

-- ----------------------------
-- Table structure for evo_sys_role
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_role`;
CREATE TABLE `evo_sys_role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `default` tinyint DEFAULT NULL,
  `created_at` bigint DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `IDX_223de54d6badbe43a5490450c3` (`name`) USING BTREE,
  UNIQUE KEY `IDX_05edc0a51f41bb16b7d8137da9` (`value`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC COMMENT='角色表';

-- ----------------------------
-- Records of evo_sys_role
-- ----------------------------
BEGIN;
INSERT INTO `evo_sys_role` (`id`, `value`, `name`, `remark`, `status`, `default`, `created_at`, `updated_at`) VALUES (1, 'admin', '管理员', '超级管理员', 1, NULL, 1712679066, 1721745647);
INSERT INTO `evo_sys_role` (`id`, `value`, `name`, `remark`, `status`, `default`, `created_at`, `updated_at`) VALUES (11, 'admin1', '测试权限添加', '12312', 0, NULL, 1712679066, 1712679485);
INSERT INTO `evo_sys_role` (`id`, `value`, `name`, `remark`, `status`, `default`, `created_at`, `updated_at`) VALUES (1000, 'system', '系统管理121', '系统管理员12312', 1, NULL, 1724469053, 1724472988);
COMMIT;

-- ----------------------------
-- Table structure for evo_sys_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_role_menu`;
CREATE TABLE `evo_sys_role_menu` (
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  PRIMARY KEY (`role_id`,`menu_id`) USING BTREE,
  KEY `IDX_35ce749b04d57e226d059e0f63` (`role_id`) USING BTREE,
  KEY `IDX_2b95fdc95b329d66c18f5baed6` (`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='菜单和角色';

-- ----------------------------
-- Records of evo_sys_role_menu
-- ----------------------------
BEGIN;
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 1);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 3);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 4);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 24);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 25);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 26);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 27);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 28);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 29);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 30);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1, 31);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 1);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 3);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 4);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 24);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 25);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 26);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 27);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 28);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 29);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 30);
INSERT INTO `evo_sys_role_menu` (`role_id`, `menu_id`) VALUES (1000, 31);
COMMIT;

-- ----------------------------
-- Table structure for evo_test_goods
-- ----------------------------
DROP TABLE IF EXISTS `evo_test_goods`;
CREATE TABLE `evo_test_goods` (
  `goods_id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `goods_type` tinyint unsigned NOT NULL DEFAULT '10' COMMENT '商品类型(10实物商品)',
  `goods_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品编码',
  `video_id` int unsigned NOT NULL DEFAULT '0' COMMENT '主图视频ID',
  `video_cover_id` int unsigned NOT NULL DEFAULT '0' COMMENT '主图视频ID',
  `selling_point` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品卖点',
  `spec_type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '商品规格(10单规格 20多规格)',
  `goods_price_min` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格(最低)',
  `goods_price_max` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格(最高)',
  `line_price_min` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '划线价格(最低)',
  `line_price_max` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '划线价格(最高)',
  `stock_total` int unsigned NOT NULL DEFAULT '0' COMMENT '库存总量(包含所有sku)',
  `deduct_stock_type` tinyint unsigned NOT NULL DEFAULT '20' COMMENT '库存计算方式(10下单减库存 20付款减库存)',
  `is_restrict` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否开启限购(0未开启 1已开启)',
  `restrict_total` int unsigned NOT NULL DEFAULT '0' COMMENT '总限购数量(0为不限制)',
  `restrict_single` int unsigned NOT NULL DEFAULT '0' COMMENT '每单限购数量(0为不限制)',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品详情',
  `sales_initial` int unsigned NOT NULL DEFAULT '0' COMMENT '初始销量',
  `sales_actual` int unsigned NOT NULL DEFAULT '0' COMMENT '实际销量',
  `delivery_id` int unsigned NOT NULL DEFAULT '0' COMMENT '配送模板ID',
  `is_points_gift` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否开启积分赠送(1开启 0关闭)',
  `is_points_discount` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否允许使用积分抵扣(1允许 0不允许)',
  `is_alone_points_discount` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '积分抵扣设置(0默认抵扣 1单独设置抵扣)',
  `points_discount_config` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '单独设置积分抵扣的配置',
  `is_enable_grade` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否开启会员折扣(1开启 0关闭)',
  `is_alone_grade` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '会员折扣设置(0默认等级折扣 1单独设置折扣)',
  `alone_grade_equity` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '单独设置折扣的配置',
  `is_ind_delivery_type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否单独设置配送方式(0关闭 1开启)',
  `delivery_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品配送方式(仅单独设置时有效)',
  `status` tinyint unsigned NOT NULL DEFAULT '10' COMMENT '商品状态(10上架 20下架)',
  `sort` int unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `is_delete` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`goods_id`),
  KEY `goods_no` (`goods_no`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='商品记录表';

-- ----------------------------
-- Records of evo_test_goods
-- ----------------------------
BEGIN;
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
