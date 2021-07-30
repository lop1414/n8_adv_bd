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

 Date: 30/07/2021 15:43:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for channel_adgroup_logs
-- ----------------------------
DROP TABLE IF EXISTS `channel_adgroup_logs`;
CREATE TABLE `channel_adgroup_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `channel_adgroup_feed_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '渠道创意关联id',
  `adgroup_feed_id` varchar(255) NOT NULL DEFAULT '' COMMENT '推广单元id',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '渠道id',
  `platform` varchar(50) NOT NULL DEFAULT '' COMMENT '平台',
  `extends` text COMMENT '扩展信息',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`) USING BTREE,
  KEY `adgroup_feed_id` (`adgroup_feed_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='渠道信息流创意日志表';

SET FOREIGN_KEY_CHECKS = 1;
