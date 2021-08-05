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

 Date: 05/08/2021 11:18:43
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for baidu_creative_reports
-- ----------------------------
DROP TABLE IF EXISTS `baidu_creative_reports`;
CREATE TABLE `baidu_creative_reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` varchar(255) NOT NULL DEFAULT '' COMMENT '账户id',
  `campaign_id` varchar(255) NOT NULL DEFAULT '' COMMENT '推广计划id',
  `adgroup_id` varchar(255) NOT NULL DEFAULT '' COMMENT '推广单元id',
  `creative_id` varchar(255) NOT NULL DEFAULT '' COMMENT '创意id',
  `stat_datetime` timestamp NULL DEFAULT NULL COMMENT '数据起始时间',
  `cost` int(11) NOT NULL DEFAULT '0' COMMENT '总花费',
  `impression` int(11) NOT NULL DEFAULT '0' COMMENT '展示数',
  `click` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
  `ocpctargettrans` int(11) NOT NULL DEFAULT '0' COMMENT '目标转化数',
  `extends` text COMMENT '扩展字段',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stat_datetime_creative_id` (`stat_datetime`,`creative_id`) USING BTREE,
  KEY `creative_id` (`creative_id`) USING BTREE,
  KEY `adgroup_id` (`adgroup_id`) USING BTREE,
  KEY `account_id` (`account_id`) USING BTREE,
  KEY `campaign_id` (`campaign_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度信息流创意数据报表';

SET FOREIGN_KEY_CHECKS = 1;
