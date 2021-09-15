/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100316
 Source Host           : localhost:3306
 Source Schema         : dikan

 Target Server Type    : MySQL
 Target Server Version : 100316
 File Encoding         : 65001

 Date: 15/09/2021 11:15:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for m_menu_detail
-- ----------------------------
DROP TABLE IF EXISTS `m_menu_detail`;
CREATE TABLE `m_menu_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu_master` int(11) NULL DEFAULT NULL,
  `detail_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `url_detail` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `sort` int(11) NULL DEFAULT NULL,
  `flag_active` smallint(1) NULL DEFAULT 1,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime(0) NULL DEFAULT NULL,
  `updatedBy` int(11) NULL DEFAULT NULL,
  `updatedDtm` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_m_master`(`id_menu_master`) USING BTREE,
  INDEX `flag_active`(`flag_active`) USING BTREE,
  INDEX `created_by`(`createdBy`) USING BTREE,
  CONSTRAINT `fk_m_master` FOREIGN KEY (`id_menu_master`) REFERENCES `m_menu_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 233 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of m_menu_detail
-- ----------------------------
INSERT INTO `m_menu_detail` VALUES (1, 2, 'Role Access', '/roleaccess', 99, 1, 1, '2019-04-17 23:14:13', NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (2, 2, 'Role Access - Create', '/roleaccess/create', NULL, 1, 1, '2019-04-18 21:44:54', NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (3, 2, 'Role Access - Edit', '/roleaccess/edit', NULL, 1, 1, '2019-04-18 21:46:03', NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (4, 2, 'Role Access - Delete', '/roleaccess/delete', NULL, 1, 1, '2019-04-18 21:46:36', NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (5, 2, 'Role Access - Active', '/roleaccess/active', NULL, 1, 1, '2019-04-18 21:47:11', NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (6, 2, 'Role Access - Detail', '/roleaccess/detail', NULL, 1, 1, '2019-04-18 21:47:39', NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (7, 1, 'Home', '/home', 1, 1, 1, '2019-04-17 23:09:20', NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (8, 2, 'Users', '/users', NULL, 1, 0, NULL, NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (9, 2, 'Users - Created', '/users/create', NULL, 1, 0, NULL, NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (10, 2, 'Users - Edit', '/users/edit', NULL, 1, 0, NULL, NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (11, 2, 'Users - Delete', '/users/delete', NULL, 1, 0, NULL, NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (12, 2, 'Users - Active', '/users/active', NULL, 1, 0, NULL, NULL, NULL);
INSERT INTO `m_menu_detail` VALUES (13, 2, 'Users - Detail', '/users/detail', NULL, 1, 0, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for m_menu_master
-- ----------------------------
DROP TABLE IF EXISTS `m_menu_master`;
CREATE TABLE `m_menu_master`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `url_master` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `fa_icon` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tree` smallint(1) NULL DEFAULT 0,
  `sorting` int(11) NULL DEFAULT NULL,
  `flag_active` smallint(1) NULL DEFAULT 1,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime(0) NULL DEFAULT NULL,
  `updatedBy` int(11) NULL DEFAULT NULL,
  `updatedDtm` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `flag_Active`(`flag_active`) USING BTREE,
  INDEX `created_by`(`createdBy`) USING BTREE,
  INDEX `sorting`(`sorting`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of m_menu_master
-- ----------------------------
INSERT INTO `m_menu_master` VALUES (1, 'Home', '/home', 'fa-home', 0, 1, 1, 1, '2019-04-17 22:59:40', NULL, NULL);
INSERT INTO `m_menu_master` VALUES (2, 'Master Data', '/masterdata', 'fa-database', 1, 6, 1, 1, '2019-04-17 23:07:24', NULL, NULL);

-- ----------------------------
-- Table structure for m_menu_relasi
-- ----------------------------
DROP TABLE IF EXISTS `m_menu_relasi`;
CREATE TABLE `m_menu_relasi`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_role` int(11) NULL DEFAULT NULL,
  `id_menu_master` int(11) NULL DEFAULT NULL,
  `id_menu_detail` int(11) NULL DEFAULT NULL,
  `flag_active` smallint(1) NULL DEFAULT 1,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime(0) NULL DEFAULT NULL,
  `updatedBy` int(11) NULL DEFAULT NULL,
  `updatedDtm` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tbl_relasimenu_ibfk_1`(`id_menu_master`) USING BTREE,
  INDEX `tbl_relasimenu_ibfk_2`(`id_menu_detail`) USING BTREE,
  INDEX `tbl_relasimenu_fk_3`(`id_role`) USING BTREE,
  INDEX `flag_active`(`flag_active`) USING BTREE,
  INDEX `created_by`(`createdBy`) USING BTREE,
  CONSTRAINT `tbl_relasimenu_fk_3` FOREIGN KEY (`id_role`) REFERENCES `m_role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_relasimenu_ibfk_1` FOREIGN KEY (`id_menu_master`) REFERENCES `m_menu_master` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_relasimenu_ibfk_2` FOREIGN KEY (`id_menu_detail`) REFERENCES `m_menu_detail` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 118 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_menu_relasi
-- ----------------------------
INSERT INTO `m_menu_relasi` VALUES (99, 2, 1, 7, 1, 1, '2020-08-22 16:41:08', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (105, 1, 1, 7, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (106, 1, 2, 1, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (107, 1, 2, 5, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (108, 1, 2, 2, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (109, 1, 2, 4, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (110, 1, 2, 6, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (111, 1, 2, 3, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (112, 1, 2, 8, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (113, 1, 2, 12, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (114, 1, 2, 9, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (115, 1, 2, 11, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (116, 1, 2, 13, 1, 1, '2021-09-11 16:32:39', NULL, NULL);
INSERT INTO `m_menu_relasi` VALUES (117, 1, 2, 10, 1, 1, '2021-09-11 16:32:39', NULL, NULL);

-- ----------------------------
-- Table structure for m_role
-- ----------------------------
DROP TABLE IF EXISTS `m_role`;
CREATE TABLE `m_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `view_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `id_role_level` tinyint(4) NULL DEFAULT NULL,
  `flag_active` smallint(1) NULL DEFAULT 1,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime(0) NULL DEFAULT NULL,
  `updatedBy` int(11) NULL DEFAULT NULL,
  `updatedDtm` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_role_level`(`id_role_level`) USING BTREE,
  INDEX `view_name`(`view_name`) USING BTREE,
  INDEX `flag_active`(`flag_active`) USING BTREE,
  INDEX `created_by`(`createdBy`) USING BTREE,
  CONSTRAINT `fk_role_level` FOREIGN KEY (`id_role_level`) REFERENCES `m_role_level` (`levelId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_role
-- ----------------------------
INSERT INTO `m_role` VALUES (1, 'Super Admin', 1, 1, 1, '2019-04-18 15:49:05', 1, '2021-09-11 16:32:39');
INSERT INTO `m_role` VALUES (2, 'Pedagang', 2, 1, 1, '2020-08-12 15:32:51', 1, '2020-08-22 16:41:07');

-- ----------------------------
-- Table structure for m_role_level
-- ----------------------------
DROP TABLE IF EXISTS `m_role_level`;
CREATE TABLE `m_role_level`  (
  `levelId` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT 'role id',
  `level_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'role text',
  `notes` text CHARACTER SET latin1 COLLATE latin1_general_ci NULL,
  PRIMARY KEY (`levelId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_role_level
-- ----------------------------
INSERT INTO `m_role_level` VALUES (1, 'Administrator', NULL);
INSERT INTO `m_role_level` VALUES (2, 'Admin Loket', NULL);
INSERT INTO `m_role_level` VALUES (3, 'Admin User', NULL);
INSERT INTO `m_role_level` VALUES (4, 'Admin Vendor', NULL);
INSERT INTO `m_role_level` VALUES (5, 'System Administrator', NULL);

-- ----------------------------
-- Table structure for m_users
-- ----------------------------
DROP TABLE IF EXISTS `m_users`;
CREATE TABLE `m_users`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'hashed login password',
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'login email',
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'full name of user',
  `mobile` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `id_role` int(11) NOT NULL,
  `flag_active` smallint(1) NOT NULL DEFAULT 1,
  `isUpdated` tinyint(3) NOT NULL DEFAULT 0,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime(0) NOT NULL,
  `updatedBy` int(11) NULL DEFAULT NULL,
  `updatedDtm` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE,
  INDEX `index1`(`username`, `name`) USING BTREE,
  INDEX `index2`(`isUpdated`, `isDeleted`) USING BTREE,
  INDEX `flagactiveindex`(`flag_active`) USING BTREE,
  INDEX `role`(`id_role`) USING BTREE,
  INDEX `created_by`(`createdBy`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1502 CHARACTER SET = latin1 COLLATE = latin1_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of m_users
-- ----------------------------
INSERT INTO `m_users` VALUES (1, 'admin', '$2y$10$lBCl1raSsBBgKf8S09uxH.UvxMcFo/LmY9w7m8bKJ0br1D9NwB7ai', 'admin@gmail.com', 'admin@gmail.com', '6282299485799', 1, 1, 0, 0, 1, '2019-04-19 08:00:59', 1, '2021-09-11 18:00:17');
INSERT INTO `m_users` VALUES (1501, 'budi', '$2y$10$PWJNyG7d8sEwfG7RjP9EdOKo9e9ZKDErzGHZQxso24ABlVIFMUx8u', 'budi@gmail.com', 'budi', '1234567', 2, 1, 0, 0, 1, '2021-09-15 03:58:23', NULL, NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2019_08_19_000000_create_failed_jobs_table', 1);

-- ----------------------------
-- Table structure for tbl_gurudansiswa
-- ----------------------------
DROP TABLE IF EXISTS `tbl_gurudansiswa`;
CREATE TABLE `tbl_gurudansiswa`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_users` int(11) NULL DEFAULT NULL,
  `nama_lengkap` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nik` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `path_photo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `full_name_photo` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_kelamin` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `flag_active` int(11) NULL DEFAULT NULL,
  `created_at` datetime(6) NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1502 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tbl_gurudansiswa
-- ----------------------------
INSERT INTO `tbl_gurudansiswa` VALUES (1, 1, 'admin', '832193819', NULL, NULL, '1', 1, '2021-09-11 21:32:01.000000', 1);
INSERT INTO `tbl_gurudansiswa` VALUES (1501, 1501, 'budi', '324234', NULL, NULL, '2', 1, '2021-09-15 03:58:23.000000', 1);

SET FOREIGN_KEY_CHECKS = 1;
