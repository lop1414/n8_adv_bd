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

 Date: 20/07/2021 17:25:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for baidu_feed_campaigns
-- ----------------------------
DROP TABLE IF EXISTS `baidu_feed_campaigns`;
CREATE TABLE `baidu_feed_campaigns` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '计划id',
  `account_id` int(10) NOT NULL COMMENT '账户id',
  `campaign_feed_name` varchar(255) NOT NULL DEFAULT '' COMMENT '计划名称',
  `subject` int(11) NOT NULL COMMENT '推广对象',
  `budget` int(11) NOT NULL COMMENT '计划预算',
  `pause` tinyint(4) NOT NULL COMMENT '计划启停',
  `status` int(11) NOT NULL COMMENT '计划状态',
  `starttime` timestamp NULL DEFAULT NULL COMMENT '推广开始时间',
  `endtime` timestamp NULL DEFAULT NULL COMMENT '推广结束时间',
  `addtime` timestamp NULL DEFAULT NULL COMMENT '添加时间',
  `extends` text COMMENT '扩展字段',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `addtime` (`addtime`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度信息流计划信息';

SET FOREIGN_KEY_CHECKS = 1;
