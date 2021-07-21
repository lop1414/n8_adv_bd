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

 Date: 21/07/2021 10:42:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for baidu_feed_adgroups
-- ----------------------------
DROP TABLE IF EXISTS `baidu_feed_adgroups`;
CREATE TABLE `baidu_feed_adgroups` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '推广单元ID',
  `account_id` bigint(10) NOT NULL COMMENT '账户ID',
  `campaign_feed_id` bigint(10) NOT NULL COMMENT '推广计划ID',
  `adgroup_feed_name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `pause` tinyint(4) NOT NULL COMMENT '启停',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `bid` int(11) NOT NULL COMMENT '出价',
  `bidtype` tinyint(4) NOT NULL COMMENT '优化目标和付费模式',
  `atp_feed_id` bigint(11) NOT NULL COMMENT '定向包ID',
  `extends` text COMMENT '扩展字段',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `campaign_feed_id` (`campaign_feed_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度信息流推广单元';

SET FOREIGN_KEY_CHECKS = 1;
