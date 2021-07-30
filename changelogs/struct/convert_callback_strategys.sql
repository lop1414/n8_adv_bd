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

 Date: 30/07/2021 15:43:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for convert_callback_strategys
-- ----------------------------
DROP TABLE IF EXISTS `convert_callback_strategys`;
CREATE TABLE `convert_callback_strategys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '策略名称',
  `extends` text COMMENT '扩展字段',
  `is_private` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否私有',
  `status` varchar(50) NOT NULL DEFAULT '' COMMENT '状态',
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='转化回传策略表';

SET FOREIGN_KEY_CHECKS = 1;
