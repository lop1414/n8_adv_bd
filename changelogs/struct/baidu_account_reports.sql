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

 Date: 05/08/2021 11:17:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for baidu_account_reports
-- ----------------------------
DROP TABLE IF EXISTS `baidu_account_reports`;
CREATE TABLE `baidu_account_reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_id` varchar(255) NOT NULL DEFAULT '' COMMENT '广告主id',
  `stat_datetime` timestamp NULL DEFAULT NULL COMMENT '数据起始时间',
  `cost` int(11) NOT NULL DEFAULT '0' COMMENT '总花费',
  `impression` int(11) NOT NULL DEFAULT '0' COMMENT '展示数',
  `click` int(11) NOT NULL DEFAULT '0' COMMENT '点击数',
  `extends` text COMMENT '扩展字段',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stat_datetime_account_id` (`stat_datetime`,`account_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度账户数据报表';

SET FOREIGN_KEY_CHECKS = 1;
