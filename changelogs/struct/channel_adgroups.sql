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

 Date: 30/07/2021 15:43:38
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for channel_adgroups
-- ----------------------------
DROP TABLE IF EXISTS `channel_adgroups`;
CREATE TABLE `channel_adgroups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `adgroup_feed_id` varchar(100) NOT NULL DEFAULT '' COMMENT '推广单元id',
  `channel_id` int(11) NOT NULL DEFAULT '0' COMMENT '渠道id',
  `platform` varchar(50) NOT NULL DEFAULT '' COMMENT '平台',
  `extends` text COMMENT '扩展字段',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `channel_ad` (`channel_id`,`adgroup_feed_id`,`platform`) USING BTREE,
  KEY `adgroup_feed_id` (`adgroup_feed_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='渠道-创意关联表';

SET FOREIGN_KEY_CHECKS = 1;
