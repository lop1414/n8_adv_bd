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

 Date: 27/07/2021 16:19:15
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for baidu_feed_accounts
-- ----------------------------
DROP TABLE IF EXISTS `baidu_feed_accounts`;
CREATE TABLE `baidu_feed_accounts` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '账户id',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT '账户余额',
  `budget` int(11) NOT NULL COMMENT '账户预算',
  `balance_package` int(11) NOT NULL COMMENT '资金包类型 0：原生资金包 1：凤巢资金包 2：代理商原生资金包',
  `user_stat` int(11) NOT NULL COMMENT '状态 1：开户金未到 2：生效 3：账户余额为0 4：被拒绝 6：审核中 7：被禁用 8：待激活 11：账户预算不足',
  `ua_status` int(11) NOT NULL COMMENT '是否开通feed产品线权限 1：已开通 2：待开通 3：不允许开通（KA客户）',
  `valid_flows` text COMMENT '可投放流量 1：手机百度 2：贴吧 4：百青藤 8：好看视频 64：百度小说',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百度信息流账户信息';

SET FOREIGN_KEY_CHECKS = 1;
