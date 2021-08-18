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

 Date: 18/08/2021 16:58:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for baidu_images
-- ----------------------------
DROP TABLE IF EXISTS `baidu_images`;
CREATE TABLE `baidu_images` (
  `id` bigint(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '图片名称',
  `size` varchar(100) NOT NULL DEFAULT '',
  `width` int(11) NOT NULL DEFAULT '0' COMMENT '宽度',
  `height` int(11) NOT NULL DEFAULT '0' COMMENT '高度',
  `source_type` int(11) NOT NULL DEFAULT '0' COMMENT '来源类型',
  `is_collect` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否收藏图',
  `url` varchar(512) NOT NULL DEFAULT '' COMMENT '预览地址',
  `format` varchar(255) NOT NULL DEFAULT '' COMMENT '格式',
  `signature` varchar(64) NOT NULL DEFAULT '' COMMENT '签名',
  `create_time` timestamp NULL DEFAULT NULL COMMENT '上传时间',
  `update_time` timestamp NULL DEFAULT NULL COMMENT '上传时间',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度图片表';

SET FOREIGN_KEY_CHECKS = 1;
