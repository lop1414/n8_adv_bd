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

 Date: 09/08/2021 12:24:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for baidu_creatives
-- ----------------------------
DROP TABLE IF EXISTS `baidu_creatives`;
CREATE TABLE `baidu_creatives` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '创意id',
  `account_id` bigint(11) NOT NULL COMMENT '账户ID',
  `adgroup_id` bigint(11) NOT NULL COMMENT '推广单元ID',
  `creative_name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `materialstyle` int(11) NOT NULL COMMENT '创意样式ID',
  `pause` tinyint(4) NOT NULL COMMENT '启停',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `idea_type` tinyint(4) NOT NULL COMMENT '创意类型',
  `show_mt` int(10) NOT NULL COMMENT '程序化创意展示样式',
  `addtime` timestamp NULL DEFAULT NULL COMMENT '添加时间',
  `extends` text COMMENT '扩展字段',
  `remark_status` varchar(50) NOT NULL DEFAULT '' COMMENT '备注状态',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `adgroup_id` (`adgroup_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度信息流创意';

SET FOREIGN_KEY_CHECKS = 1;
