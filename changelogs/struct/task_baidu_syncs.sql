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

 Date: 02/08/2021 16:14:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for task_baidu_syncs
-- ----------------------------
DROP TABLE IF EXISTS `task_baidu_syncs`;
CREATE TABLE `task_baidu_syncs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '父任务id',
  `account_id` varchar(255) NOT NULL DEFAULT '' COMMENT '账户id',
  `sync_type` varchar(50) NOT NULL DEFAULT '' COMMENT '同步类型',
  `exec_status` varchar(50) NOT NULL DEFAULT '' COMMENT '执行状态',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `extends` text COMMENT '扩展字段',
  `fail_data` text COMMENT '失败数据',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`) USING BTREE,
  KEY `task_id` (`task_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度同步任务表';

SET FOREIGN_KEY_CHECKS = 1;
