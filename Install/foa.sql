/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : fffz

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-01-10 17:45:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for oa_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `oa_admin_role`;
CREATE TABLE `oa_admin_role` (
  `role_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(30) DEFAULT NULL COMMENT '角色名称',
  `act_list` text COMMENT '权限列表',
  `role_desc` varchar(255) DEFAULT NULL COMMENT '角色描述',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_admin_role
-- ----------------------------
INSERT INTO `oa_admin_role` VALUES ('2', '经理', '3,4,5,6,7,8,9,10,11,12,13,27,39,41', '管理组员');
INSERT INTO `oa_admin_role` VALUES ('6', '组员', '3,4,5,6,7,8,9,10,11,12,13,27', '编写代码');
INSERT INTO `oa_admin_role` VALUES ('1', '超级管理员', 'all', '管理全站');

-- ----------------------------
-- Table structure for oa_info
-- ----------------------------
DROP TABLE IF EXISTS `oa_info`;
CREATE TABLE `oa_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `is_read` tinyint(3) DEFAULT '0' COMMENT '是否需要签收',
  `tos` int(10) NOT NULL DEFAULT '0' COMMENT '登陆人ID',
  `from` int(10) NOT NULL COMMENT '登陆人名称',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `type` tinyint(3) NOT NULL DEFAULT '0' COMMENT '删除标记',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_info
-- ----------------------------

-- ----------------------------
-- Table structure for oa_mail
-- ----------------------------
DROP TABLE IF EXISTS `oa_mail`;
CREATE TABLE `oa_mail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `content` text NOT NULL COMMENT '内容',
  `from` varchar(255) DEFAULT NULL COMMENT '发件人',
  `to` varchar(255) DEFAULT NULL COMMENT '收件人',
  `read` tinyint(1) NOT NULL DEFAULT '0' COMMENT '已读',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `user_name` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `is_del` tinyint(3) NOT NULL DEFAULT '0' COMMENT '删除标记',
  `type` tinyint(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_mail
-- ----------------------------

-- ----------------------------
-- Table structure for oa_mail_account
-- ----------------------------
DROP TABLE IF EXISTS `oa_mail_account`;
CREATE TABLE `oa_mail_account` (
  `smtp_server` varchar(50) NOT NULL,
  `id` mediumint(6) NOT NULL,
  `smtp_port` int(6) DEFAULT NULL COMMENT '邮件地址',
  `smtp_user` varchar(50) NOT NULL DEFAULT '' COMMENT '邮件显示名称',
  `smtp_pwd` varchar(50) NOT NULL DEFAULT '' COMMENT 'pop服务器',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '邮件密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='think_user_info';

-- ----------------------------
-- Records of oa_mail_account
-- ----------------------------

-- ----------------------------
-- Table structure for oa_system_config
-- ----------------------------
DROP TABLE IF EXISTS `oa_system_config`;
CREATE TABLE `oa_system_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `val` varchar(255) DEFAULT NULL,
  `is_del` tinyint(3) NOT NULL DEFAULT '0',
  `sort` varchar(20) DEFAULT NULL,
  `pid` int(11) DEFAULT '0',
  `data_type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_system_config
-- ----------------------------
INSERT INTO `oa_system_config` VALUES ('1', 'login_verify_code', '', '0', '0', null, '0', 'system');

-- ----------------------------
-- Table structure for oa_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `oa_system_menu`;
CREATE TABLE `oa_system_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '权限名字',
  `group` varchar(20) DEFAULT NULL COMMENT '所属分组',
  `right` text COMMENT '权限码(控制器+动作)',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '删除状态 1删除,0正常',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_system_menu
-- ----------------------------
INSERT INTO `oa_system_menu` VALUES ('3', '工作日志', 'work', 'WorkController@worklog', '0');
INSERT INTO `oa_system_menu` VALUES ('4', '工作列表', 'work', 'WorkController@worklist', '0');
INSERT INTO `oa_system_menu` VALUES ('5', '任务列表', 'work', 'WorkController@task', '0');
INSERT INTO `oa_system_menu` VALUES ('6', '任务状态', 'work', 'WorkController@status', '0');
INSERT INTO `oa_system_menu` VALUES ('7', '已发送', 'mail', 'MailController@maillist', '0');
INSERT INTO `oa_system_menu` VALUES ('8', '写信', 'mail', 'MailController@sendemail', '0');
INSERT INTO `oa_system_menu` VALUES ('9', '草稿箱', 'mail', 'MailController@draftmail', '0');
INSERT INTO `oa_system_menu` VALUES ('10', '垃圾箱', 'mail', 'MailController@trashmail', '0');
INSERT INTO `oa_system_menu` VALUES ('11', '草稿箱', 'mail', 'MailController@draftmail', '0');
INSERT INTO `oa_system_menu` VALUES ('12', '邮件设置', 'mail', 'MailController@config', '0');
INSERT INTO `oa_system_menu` VALUES ('13', '我的信息', 'info', 'InfoController@index', '0');
INSERT INTO `oa_system_menu` VALUES ('27', '工作统计', 'form', 'FormController@index', '0');
INSERT INTO `oa_system_menu` VALUES ('39', '用户管理', 'manager', 'ManagerController@user', '0');
INSERT INTO `oa_system_menu` VALUES ('40', '角色管理', 'manager', 'ManagerController@role', '0');

-- ----------------------------
-- Table structure for oa_task
-- ----------------------------
DROP TABLE IF EXISTS `oa_task`;
CREATE TABLE `oa_task` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(255) DEFAULT NULL COMMENT '内容',
  `expected_time` int(11) DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `user_name` varchar(20) DEFAULT NULL,
  `dept_id` int(11) DEFAULT NULL,
  `dept_name` varchar(20) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` tinyint(3) DEFAULT '0',
  `is_del` tinyint(3) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_task
-- ----------------------------

-- ----------------------------
-- Table structure for oa_user
-- ----------------------------
DROP TABLE IF EXISTS `oa_user`;
CREATE TABLE `oa_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `emp_no` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL,
  `letter` varchar(10) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `sex` varchar(50) NOT NULL,
  `birthday` date DEFAULT NULL,
  `last_login_ip` varchar(40) DEFAULT NULL,
  `login_count` int(8) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `duty` varchar(2000) NOT NULL,
  `office_tel` varchar(20) NOT NULL,
  `mobile_tel` varchar(20) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `is_del` tinyint(3) NOT NULL DEFAULT '0',
  `openid` varchar(50) DEFAULT NULL,
  `westatus` tinyint(3) DEFAULT '0',
  `init_pwd` tinyint(3) DEFAULT NULL,
  `pay_pwd` varchar(32) DEFAULT NULL,
  `role_id` tinyint(2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`emp_no`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_user
-- ----------------------------
INSERT INTO `oa_user` VALUES ('1', 'admin', '管理员', 'GLY', '21232f297a57a5a743894a0e4a801fc3', '1', '1', 'male', '2013-09-18', '127.0.0.1', '3134', 'Uploads/emp_pic/1.jpeg', '810210908@qq.com', '1231254123123', '5086-2222-2222', '12123123', '1222907803', '1431500668', '0', '', '1', '1', 'c81e728d9d4c2f636f067f89cc14862c', '1');
INSERT INTO `oa_user` VALUES ('3', 'zs', '张三', 'FZS', 'e10adc3949ba59abbe56e057f20f883e', '24', '1', 'male', '2017-11-30', '127.0.0.1', null, '', '1255896643@qq.com', '', '', '', '1514268942', '1514287961', '0', '1', '1', null, null, '6');
INSERT INTO `oa_user` VALUES ('4', 'adminer', '李四', '', '21232f297a57a5a743894a0e4a801fc3', '0', '0', '', null, null, null, null, '', '', '', '', '0', '0', '0', null, '0', null, null, '6');
INSERT INTO `oa_user` VALUES ('5', 'admin3', '王五', '', '21232f297a57a5a743894a0e4a801fc3', '0', '0', '', null, '127.0.0.1', null, null, '', '', '', '', '1515547560', '0', '0', null, '0', null, null, '2');

-- ----------------------------
-- Table structure for oa_worklog
-- ----------------------------
DROP TABLE IF EXISTS `oa_worklog`;
CREATE TABLE `oa_worklog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_name` varchar(20) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `content` varchar(600) DEFAULT NULL,
  `plan` varchar(600) DEFAULT NULL,
  `is_del` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_worklog
-- ----------------------------
