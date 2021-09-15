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

 Date: 15/09/2021 09:59:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for page_clicks
-- ----------------------------
DROP TABLE IF EXISTS `page_clicks`;
CREATE TABLE `page_clicks` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `bd_vid` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `ua` varchar(1024) NOT NULL DEFAULT '' COMMENT 'user agent',
  `click_at` timestamp NULL DEFAULT NULL COMMENT '点击时间',
  `extends` text NOT NULL COMMENT '扩展字段',
  `fix_status` varchar(50) NOT NULL COMMENT '修正状态',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `ip` (`ip`) USING BTREE,
  KEY `bd_vid` (`bd_vid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度页面转发点击表';
SET FOREIGN_KEY_CHECKS = 1;
