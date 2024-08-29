/*
 Navicat Premium Data Transfer

 Source Server         : 谭靖服务器
 Source Server Type    : MySQL
 Source Server Version : 80200 (8.2.0)
 Source Host           : 118.89.77.123:3306
 Source Schema         : evo_php_admin

 Target Server Type    : MySQL
 Target Server Version : 80200 (8.2.0)
 File Encoding         : 65001

 Date: 29/08/2024 09:13:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for evo_region
-- ----------------------------
DROP TABLE IF EXISTS `evo_region`;
CREATE TABLE `evo_region`  (
  `adcode` int UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '名称',
  `pinyin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '拼音',
  `lat` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '纬度',
  `lng` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '经度',
  `level` tinyint UNSIGNED NULL DEFAULT NULL COMMENT '等级',
  `parent_id` int UNSIGNED NOT NULL DEFAULT 0
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of evo_region
-- ----------------------------

-- ----------------------------
-- Table structure for evo_sys_admin
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_admin`;
CREATE TABLE `evo_sys_admin`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '账户',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '密码',
  `created_at` bigint NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint NULL DEFAULT NULL COMMENT '更新时间',
  `deleted_at` bigint NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username` ASC) USING BTREE INVISIBLE
) ENGINE = InnoDB AUTO_INCREMENT = 10000 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '系统用户表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of evo_sys_admin
-- ----------------------------
INSERT INTO `evo_sys_admin` VALUES (1, 'admin', '$2y$10$qGd7As4hCMJdwDU4KMMgV.U4W9JVSLvs35wZERKEwr8fiqzpX8Fpu', 1717655358, 1717655358, NULL);

-- ----------------------------
-- Table structure for evo_sys_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_admin_role`;
CREATE TABLE `evo_sys_admin_role`  (
  `admin_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`admin_id`, `role_id`) USING BTREE,
  INDEX `IDX_96311d970191a044ec048011f4`(`admin_id` ASC) USING BTREE,
  INDEX `IDX_6d61c5b3f76a3419d93a421669`(`role_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员角色' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of evo_sys_admin_role
-- ----------------------------
INSERT INTO `evo_sys_admin_role` VALUES (1, 1);

-- ----------------------------
-- Table structure for evo_sys_menu
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_menu`;
CREATE TABLE `evo_sys_menu`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NULL DEFAULT NULL,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `permission` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type` tinyint NOT NULL DEFAULT 0,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `order_no` int NULL DEFAULT 0,
  `component` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keep_alive` tinyint NOT NULL DEFAULT 1,
  `show` tinyint NOT NULL DEFAULT 1,
  `status` tinyint NOT NULL DEFAULT 1,
  `is_ext` tinyint NOT NULL DEFAULT 0,
  `ext_open_mode` tinyint NOT NULL DEFAULT 1,
  `active_menu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` bigint NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1003 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '菜单表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of evo_sys_menu
-- ----------------------------
INSERT INTO `evo_sys_menu` VALUES (1, 0, '/system', '系统管理', '', 0, 'ant-design:setting-outlined', 255, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1724466472);
INSERT INTO `evo_sys_menu` VALUES (3, 1, '/system/role', '角色管理', 'system:role:list', 1, 'ep:user', 1, 'system/role/index', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (4, 1, '/system/menu', '菜单管理', 'system:menu:list', 1, 'ep:menu', 2, 'system/menu/index', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (24, 3, '', '新增', 'system:role:create', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (25, 3, '', '删除', 'system:role:delete', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (26, 3, '', '修改', 'system:role:update', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (27, 3, '', '查询', 'system:role:read', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (28, 4, NULL, '新增', 'system:menu:create', 2, NULL, 0, NULL, 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (29, 4, NULL, '删除', 'system:menu:delete', 2, NULL, 0, NULL, 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (30, 4, '', '修改', 'system:menu:update', 2, '', 0, '', 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (31, 4, NULL, '查询', 'system:menu:read', 2, NULL, 0, NULL, 0, 1, 1, 0, 1, NULL, 1712472121, 1712472121);
INSERT INTO `evo_sys_menu` VALUES (1000, 0, '/develop', '开发工具', NULL, 0, 'ant-design:tool-outlined', 254, NULL, 1, 1, 1, 0, 1, NULL, 1724809231, 1724812511);
INSERT INTO `evo_sys_menu` VALUES (1001, 1000, '/develop/generate', '代码生成', 'develop:generate:code', 1, 'ant-design:crown-outlined', 254, 'develop/generate/index', 1, 1, 1, 0, 1, NULL, 1724809416, 1724812505);
INSERT INTO `evo_sys_menu` VALUES (1002, 1, '/system/storage', '存储管理', 'system:storage:list', 1, 'ant-design:folder-open-filled', 255, 'system/storage/index', 0, 1, 1, 0, 1, NULL, 1724845216, 1724845216);

-- ----------------------------
-- Table structure for evo_sys_role
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_role`;
CREATE TABLE `evo_sys_role`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` tinyint NULL DEFAULT 1,
  `default` tinyint NULL DEFAULT NULL,
  `created_at` bigint NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` bigint NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `IDX_223de54d6badbe43a5490450c3`(`name` ASC) USING BTREE,
  UNIQUE INDEX `IDX_05edc0a51f41bb16b7d8137da9`(`value` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1001 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of evo_sys_role
-- ----------------------------
INSERT INTO `evo_sys_role` VALUES (1, 'admin', '管理员', '超级管理员', 1, NULL, 1712679066, 1724845244);
INSERT INTO `evo_sys_role` VALUES (11, 'admin1', '测试权限添加', '12312', 0, NULL, 1712679066, 1712679485);
INSERT INTO `evo_sys_role` VALUES (1000, 'system', '系统管理121', '系统管理员12312', 1, NULL, 1724469053, 1724472988);

-- ----------------------------
-- Table structure for evo_sys_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `evo_sys_role_menu`;
CREATE TABLE `evo_sys_role_menu`  (
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  PRIMARY KEY (`role_id`, `menu_id`) USING BTREE,
  INDEX `IDX_35ce749b04d57e226d059e0f63`(`role_id` ASC) USING BTREE,
  INDEX `IDX_2b95fdc95b329d66c18f5baed6`(`menu_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '菜单和角色' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of evo_sys_role_menu
-- ----------------------------
INSERT INTO `evo_sys_role_menu` VALUES (1, 1);
INSERT INTO `evo_sys_role_menu` VALUES (1, 3);
INSERT INTO `evo_sys_role_menu` VALUES (1, 4);
INSERT INTO `evo_sys_role_menu` VALUES (1, 24);
INSERT INTO `evo_sys_role_menu` VALUES (1, 25);
INSERT INTO `evo_sys_role_menu` VALUES (1, 26);
INSERT INTO `evo_sys_role_menu` VALUES (1, 27);
INSERT INTO `evo_sys_role_menu` VALUES (1, 28);
INSERT INTO `evo_sys_role_menu` VALUES (1, 29);
INSERT INTO `evo_sys_role_menu` VALUES (1, 30);
INSERT INTO `evo_sys_role_menu` VALUES (1, 31);
INSERT INTO `evo_sys_role_menu` VALUES (1, 1000);
INSERT INTO `evo_sys_role_menu` VALUES (1, 1001);
INSERT INTO `evo_sys_role_menu` VALUES (1, 1002);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 1);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 3);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 4);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 24);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 25);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 26);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 27);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 28);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 29);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 30);
INSERT INTO `evo_sys_role_menu` VALUES (1000, 31);

-- ----------------------------
-- Table structure for evo_test_goods
-- ----------------------------
DROP TABLE IF EXISTS `evo_test_goods`;
CREATE TABLE `evo_test_goods`  (
  `goods_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `goods_type` tinyint UNSIGNED NOT NULL DEFAULT 10 COMMENT '商品类型(10实物商品)',
  `goods_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品编码',
  `video_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '主图视频ID',
  `video_cover_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '主图视频ID',
  `selling_point` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品卖点',
  `spec_type` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '商品规格(10单规格 20多规格)',
  `goods_price_min` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '商品价格(最低)',
  `goods_price_max` decimal(10, 2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '商品价格(最高)',
  `line_price_min` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '划线价格(最低)',
  `line_price_max` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '划线价格(最高)',
  `stock_total` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '库存总量(包含所有sku)',
  `deduct_stock_type` tinyint UNSIGNED NOT NULL DEFAULT 20 COMMENT '库存计算方式(10下单减库存 20付款减库存)',
  `is_restrict` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否开启限购(0未开启 1已开启)',
  `restrict_total` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '总限购数量(0为不限制)',
  `restrict_single` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '每单限购数量(0为不限制)',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品详情',
  `sales_initial` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '初始销量',
  `sales_actual` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '实际销量',
  `delivery_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '配送模板ID',
  `is_points_gift` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否开启积分赠送(1开启 0关闭)',
  `is_points_discount` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否允许使用积分抵扣(1允许 0不允许)',
  `is_alone_points_discount` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '积分抵扣设置(0默认抵扣 1单独设置抵扣)',
  `points_discount_config` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '单独设置积分抵扣的配置',
  `is_enable_grade` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否开启会员折扣(1开启 0关闭)',
  `is_alone_grade` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员折扣设置(0默认等级折扣 1单独设置折扣)',
  `alone_grade_equity` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '单独设置折扣的配置',
  `is_ind_delivery_type` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否单独设置配送方式(0关闭 1开启)',
  `delivery_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品配送方式(仅单独设置时有效)',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 10 COMMENT '商品状态(10上架 20下架)',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序(数字越小越靠前)',
  `is_delete` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除',
  `store_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '商城ID',
  `create_time` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`goods_id`) USING BTREE,
  INDEX `goods_no`(`goods_no` ASC) USING BTREE,
  INDEX `store_id`(`store_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10001 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '商品记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of evo_test_goods
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
