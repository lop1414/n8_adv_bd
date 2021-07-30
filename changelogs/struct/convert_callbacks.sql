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

 Date: 30/07/2021 15:43:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for convert_callbacks
-- ----------------------------
DROP TABLE IF EXISTS `convert_callbacks`;
CREATE TABLE `convert_callbacks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `click_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '点击id',
  `convert_type` varchar(50) NOT NULL DEFAULT '' COMMENT '转化类型',
  `convert_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '转化id',
  `n8_union_guid` bigint(20) NOT NULL DEFAULT '0' COMMENT 'n8全局用户id',
  `n8_union_channel_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'n8渠道id',
  `convert_at` timestamp NULL DEFAULT NULL COMMENT '转化时间',
  `exec_status` varchar(50) NOT NULL DEFAULT '' COMMENT '执行状态',
  `convert_callback_status` varchar(50) NOT NULL DEFAULT '' COMMENT '回传状态',
  `extends` text COMMENT '扩展字段',
  `fail_data` text COMMENT '失败数据',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  `callback_at` timestamp NULL DEFAULT NULL COMMENT '回传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `convert` (`convert_type`,`convert_id`) USING BTREE,
  KEY `click_id` (`click_id`) USING BTREE,
  KEY `created_at` (`created_at`) USING BTREE,
  KEY `convert_at` (`convert_at`) USING BTREE,
  KEY `n8_union_user` (`n8_union_guid`,`n8_union_channel_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='转化上报日志表';

SET FOREIGN_KEY_CHECKS = 1;
