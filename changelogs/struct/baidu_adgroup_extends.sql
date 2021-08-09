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

 Date: 09/08/2021 12:20:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for baidu_adgroup_extends
-- ----------------------------
DROP TABLE IF EXISTS `baidu_adgroup_extends`;
CREATE TABLE `baidu_adgroup_extends` (
  `adgroup_id` varchar(100) NOT NULL DEFAULT '' COMMENT '推广单元id',
  `convert_callback_strategy_id` int(11) NOT NULL DEFAULT '0' COMMENT '回传策略id',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`adgroup_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度信息流创意扩展表';

SET FOREIGN_KEY_CHECKS = 1;
