/*
 Navicat Premium Data Transfer

 Source Server         : 虚拟机 192.168.10.10
 Source Server Type    : MySQL
 Source Server Version : 50731
 Source Host           : localhost:3306
 Source Schema         : n8_adv_bd

 Target Server Type    : MySQL
 Target Server Version : 50731
 File Encoding         : 65001

 Date: 21/07/2021 10:42:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for baidu_accounts
-- ----------------------------
DROP TABLE IF EXISTS `baidu_accounts`;
CREATE TABLE `baidu_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` bigint(11) NOT NULL COMMENT '账户id',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `token` varchar(50) NOT NULL COMMENT 'token',
  `ocpc_token` varchar(50) NOT NULL COMMENT 'OCPCToken',
  `rebate` int(10) DEFAULT NULL COMMENT '反点',
  `password` varchar(255) NOT NULL COMMENT '账户密码',
  `parent_id` varchar(50) DEFAULT NULL COMMENT '父级id',
  `status` varchar(50) NOT NULL DEFAULT '' COMMENT '状态',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `extends` text COMMENT '扩展字段',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `account_id` (`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='账户信息';

SET FOREIGN_KEY_CHECKS = 1;
