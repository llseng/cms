<?php
return [
	/*
	//用户信息表
	"im_users" => "CREATE TABLE `im_users` (

		`id` int(5) unsigned NOT NULL AUTO_INCREMENT,

		`uid` int(11) unsigned NOT NULL COMMENT '用户ID',

		`nickname` varchar(32) DEFAULT NULL COMMENT '昵称',

		`username` varchar(32) NOT NULL COMMENT '用户名',

		`password` char(64) DEFAULT NULL,

		`email` varchar(32) DEFAULT NULL,

		`mobile` char(11) DEFAULT NULL,

		`avatar` varchar(255) NOT NULL,

		`sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0：未知 1男 2女',

		`age` tinyint(3) NOT NULL DEFAULT '0' COMMENT '年龄',

		`birthiday` int(11) DEFAULT NULL COMMENT '生日',

		`identification` varchar(20) DEFAULT NULL COMMENT '标识',

		`identification_uid` int(11) DEFAULT NULL,

		`status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',

		`longitude` decimal(10,6) DEFAULT NULL,

		`latitude` decimal(10,6) DEFAULT NULL,

		`city` varchar(255) DEFAULT '',

		`addtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'chuangjianshijian',

		`uptime` int(11) unsigned NOT NULL default '0' comment '修改时间',

		`phpsessid` varchar(32) not null comment '用户sessionID',

		PRIMARY KEY (`id`,`uid`)

	  ) ENGINE=INNODB DEFAULT CHARSET=utf8",

	//删除原有存储过程
	"drop-subscribe_message" => "DROP PROCEDURE IF EXISTS subscribe_message",
	//拼接预约看房信息
	"subscribe_message" => "CREATE PROCEDURE subscribe_message(in par_uid int,in par_to_uid int,in par_content varchar(255),in par_addtime int,in par_order_id int)
	BEGIN

		DECLARE err tinyint(1) DEFAULT 0;

		DECLARE CONTINUE HANDLER FOR SQLWARNING,NOT FOUND,SQLEXCEPTION SET err = 1;

		START TRANSACTION;
		
		insert into im_message(`uid`,`to_uid`,`type_id`,`content`,`addtime`) values(par_uid,par_to_uid,3,par_content,par_addtime);

		update im_subscribe set `ms_id`=last_insert_id() where `order_id`=par_order_id;

		IF err THEN
			ROLLBACK;
			delete from im_subscribe where `order_id`=par_order_id;
		ELSE
			COMMIT;
		END IF;

		select err;

	END;",
	*/
	/*
	//实名认证信息表
	"pay_real" => "CREATE TABLE pay_real(

		`id` int(11) unsigned not null auto_increment comment '主键ID',

		`mch_id` int(11) unsigned not null comment '商户ID',

		`idcard` varchar(20) not null comment '身份证号',

		`real_name` varchar(20) not null comment '真实姓名',

		`card_no` int(11) unsigned not null,

		`addr_code` int(11) unsigned not null comment '地区编码',

		`birth` date default '0000-00-00' comment '出生日期',

		`sex` tinyint(1) unsigned not null default 0 comment '性别',

		`length` tinyint(3) unsigned not null default 0 comment '身份证位数',

		`check_bit` varchar(2) not null default '' comment '身份证最后一位',

		`addr` varchar(32) not null default '' comment '身份证所在地',

		`msg` varchar(32) not null default '' commnet '验证消息',

		`status` tinyint(1) unsigned not null default 0 comment '认证状态',

		`create_time` int(11) unsigned not null default 0 comment '创建时间搓',

		`alter_time` int(11) unsigned not null default 0 comment '修改时间',

		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',

		primary key (`id`),

		unique key `idcard`(`idcard`),

		key `mch_id`(`mch_id`),

		constraint `mchid` foreign key (`mch_id`) 
		references `pay_mch`(`mch_id`)
		on update restrict
		on delete restrict

	)ENGINE=innodb default charset=utf8 comment='实名认证信息表'",
	*/
	
	//测试表
	"cen_test" => "CREATE TABLE cen_test(
		
		`id` int(11) unsigned not null auto_increment,

		`name` varchar(50) not null comment '名',
		
		`nick` varchar(20) not null comment '昵称',
		
		`create_time` int(11) not null default 0 comment '创建时间',
		
		`update_time` int(11) not null default 0 comment '修改时间',
		
		`status` tinyint(1) unsigned not null default 0 comment '启用状态',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key (`id`),
		
		unique key `name`(`name`),
		
		key `nick`(`nick`),
		
		key `create_time`(`create_time`)
		
	)engine=innodb default charset=utf8 comment='测试表'",
	
	//系统基础配置
	"cen_config" => "CREATE TABLE cen_config(

		`id` smallint(11) unsigned not null AUTO_INCREMENT comment '主键',

		`name` varchar(50) not null comment '配置key值',

		`value` varchar(512) not null comment '配置value值',

		`group` varchar(50) not null default '' comment '配置分组',

		`intro` varchar(50) not null comment '配置描述',

		`addtime` int(11) unsigned not null default 0 comment '创建时间',

		`settime` int(11) unsigned not null default 0 comment '修改时间',

		PRIMARY KEY (`id`),

		UNIQUE KEY `gn`(`group`,`name`)

	)ENGINE=MYISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8 COMMENT='支付系统基础配置'",
	
	//管理员列表
	'cen_admin' => "CREATE table cen_admin(
	
		`id` smallint(5) unsigned not null auto_increment comment '主键',
		
		`name` varchar(20) not null comment '管理员名',
		
		`nick` varchar(20) not null comment '管理员昵称',
		
		`pass` varchar(32) not null comment '管理员密码',
		
		`role_id` int(11) unsigned not null default 0 comment '角色ID(外键)',
		
		`intro` varchar(255) not null comment '管理简介',
		
		`phone` varchar(12) not null comment '手机号',
		
		`email` varchar(32) not null comment '邮箱',
		
		`create_time` int(11) not null default 0 comment '创建时间',
		
		`update_time` int(11) not null default 0 comment '修改时间',
		
		`create_ip` varchar(20) not null default '' comment '创建ip',
		
		`last_ip` varchar(20) comment '最后登录IP',
		
		`last_time` int(11) unsigned not null default 0 comment '最后登录时间',
		
		`status` tinyint(1) unsigned not null default 1 comment '启用状态',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key (`id`),
		
		unique key `name`(`name`),
		
		key `nick`(`nick`),
		
		key `role_id`(`role_id`),
		
		key `create_time`(`create_time`)
	
	)engine=innodb default charset=utf8 comment='管理员列表'",
	
	//商户列表
	'cen_mch' => "CREATE table cen_mch(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`name` varchar(20) not null comment '用户名',
		
		`nick` varchar(20) not null comment '昵称',
		
		`phone` varchar(12) not null comment '手机号',
		
		`email` varchar(64) not null default '' comment '邮箱',
		
		`password` varchar(32) not null comment '登录密码',
		
		`paypwd` varchar(32) not null comment '支付密码',
		
		`codekey` varchar(32) not null comment '接口秘钥',
		
		`idcard` varchar(18) not null default 0 comment '身份证号',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间搓',
		
		`create_ip` varchar(20) not null default '' comment '创建ip',
		
		`last_ip` varchar(20) comment '最后登录IP',
		
		`last_time` int(11) unsigned not null default 0 comment '最后登录时间',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		unique key `name`(`name`),
		
		unique key `phone`(`phone`),
		
		key `email`(`email`),
		
		key `create_time`(`create_time`)
		
	)engine=innodb default charset=utf8 auto_increment=1000 comment='商户列表'",
	
	//应用列表
	'cen_apply' => "CREATE table cen_apply(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`mch_id` int(11) unsigned not null comment '商户ID(外键)',
		
		`name` varchar(20) not null comment '应用名',
		
		`nick` varchar(20) not null comment '昵称',
		
		`intro` varchar(200) not null comment '应用简介',
		
		`sign` varchar(32) not null comment '应用秘钥',
		
		`status` tinyint(1) unsigned not null default 0 comment '启用状态',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间搓',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		unique key `name`(`name`),
		
		key `nick`(`nick`),
		
		key `create_time`(`create_time`),
		
		constraint `mchid` foreign key (`mch_id`)
		references `cen_mch`(`id`)
		
	)engine=innodb default charset=utf8 auto_increment=1000 comment='商户列表'",
	
	//手机号列表
	'cen_phone' => "CREATE table cen_phone(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`phone` varchar(12) not null comment '手机号码',
		
		`company` varchar(5) not null comment '运营商',
		
		`province` varchar(10) not null comment '省份',
		
		`city` varchar(10) not null comment '城市',
		
		`areacode` mediumint(5) unsigned not null default 0 comment '区号',
		
		`zip` mediumint(6) unsigned null default 0 comment '邮编',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间搓',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		unique key `phone`(`phone`),
		
		key `create_time`(`create_time`)
		
	)engine=innodb default charset=utf8 auto_increment=1000 comment='手机号码列表'",
	
	//手机短信列表
	'cen_smslist' => "CREATE table cen_smslist(
	
		`id` int(11) unsigned not null auto_increment comment '主键ID',
		
		`biz_id` varchar(20) not null comment '第三方流水号',
		
		`mch_id` int(11) unsigned not null comment '商户ID',
		
		`apply_id` int(11) unsigned not null comment '应用ID',
		
		`phone` varchar(12) not null comment '接收手机号',
		
		`params` varchar(255) not null comment '请求参数',
		
		`product` varchar(10) not null comment '签名',
		
		`temp_id` varchar(20) not null comment '模板ID',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		key `mch_id`(`mch_id`),
		
		key `apply_id`(`apply_id`),
		
		key `phone`(`phone`),
		
		key `create_time`(`create_time`)
		
	)engine=innodb default charset=utf8 comment='手机短信列表'",
	
	//身份证信息列表
	'cen_card' => "CREATE table cen_card(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`idcard` varchar(20) not null comment '身份证号码',
		
		`real_name` varchar(5) not null comment '真实姓名',

		`card_no` int(11) unsigned not null,

		`addr_code` int(11) unsigned not null comment '地区编码',

		`birth` date default '0000-00-00' comment '出生日期',

		`sex` tinyint(1) unsigned not null default 0 comment '性别',

		`addr` varchar(32) not null default '' comment '身份证所在地',
		
		`msg` varchar(20) not null default '' comment '接口返回信息',
		
		`status` tinyint(1) unsigned not null default 0 comment '认证状态',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间搓',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		unique key `idcard`(`idcard`),
		
		key `create_time`(`create_time`)
		
	)engine=innodb default charset=utf8 auto_increment=1000 comment='身份证信息列表'",
	
	//手机应用关联表
	'cen_apply_phone' => "CREATE table cen_apply_phone(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`apply_id` int(11) unsigned not null comment '应用ID(外键)',
		
		`apply_name` varchar(20) not null comment '应用名',
		
		`phone_id` int(11) unsigned not null comment '手机ID(外键)',
		
		`phone` varchar(12) not null comment '手机号',
		
		`status` tinyint(1) unsigned not null default 0 comment '启用状态',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间搓',
		
		`alter_time` int(11) unsigned not null default 0 comment '修改时间搓',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		unique key `apply_phone`(`apply_id`,`phone_id`),
		
		key `create_time`(`create_time`),
		
		constraint `applyid` foreign key (`apply_id`)
		references `cen_apply`(`id`),
		
		constraint `phoneid` foreign key (`phone_id`)
		references `cen_phone`(`id`)
		
	)engine=innodb default charset=utf8 comment='商户列表'",
	
	//接口表
	'cen_api' => "CREATE table cen_api(
		
		`id` smallint(5) unsigned not null auto_increment comment '主键',
		
		`name` varchar(20) not null comment '接口名',
		
		`nick` varchar(20) not null comment '接口昵称',
		
		`intro` varchar(255) not null comment '接口简介',
		
		`status` tinyint(1) unsigned not null default 0 comment '开启状态',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间',
		
		`update_time` int(11) unsigned not null default 0 comment '修改时间',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key (`id`),
		
		unique key `name`(`name`),
		
		key `nick`(`nick`),
		
		key `create_time`(`create_time`)
		
	)engine=innodb default charset=utf8 comment='API列表'",
	
	//商户接口关联表
	'cen_mch_api' => "CREATE table cen_mch_api(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`mch_id` int(11) unsigned not null comment '商户ID(外键)',
		
		`api_id` int(11) unsigned not null comment '接口ID(外键)',
		
		`amount` int(11) unsigned not null default 0 comment '使用额度',
		
		`sign` varchar(32) not null default '' comment '应用秘钥',
		
		`status` tinyint(1) unsigned not null default 0 comment '启用状态',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间',
		
		`update_time` int(11) unsigned not null default 0 comment '修改时间',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key (`id`),
		
		unique key `mch_api`(`mch_id`,`api_id`),
		
		key `create_time`(`create_time`)
	
	)engine=innodb auto_increment=1000 default charset=utf8 comment='商户接口关联表'",
	
	//商户API白名单
	'cen_mch_api_ipwhite' => "CREATE table cen_mch_api_ipwhite(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`mch_id` int(11) unsigned not null comment '商户ID(外键)',
		
		`api_id` int(11) unsigned not null comment '接口ID(外键)',
		
		`mch_api_id` int(11) unsigned not null comment '商户APIID(外键)',
		
		`ip` varchar(20) not null default '' comment 'ip白名单',
		
		`status` tinyint(1) unsigned not null default 0 comment '启用状态',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间',
		
		`update_time` int(11) unsigned not null default 0 comment '修改时间',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key (`id`),
		
		key `mch_id`(`mch_id`),
		
		key `api_id`(`api_id`),
		
		key `mch_api_id`(`mch_api_id`),
		
		key `create_time`(`create_time`)
	
	)engine=innodb default charset=utf8 comment='商户API白名单'",
	
	//API请求记录
	'cen_api_log' => "CREATE table cen_api_log(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`mch_id` int(11) unsigned not null comment '商户ID(外键)',
		
		`api_id` int(11) unsigned not null comment '接口ID(外键)',
		
		`mch_api_id` int(11) unsigned not null comment '商户APIID(外键)',
		
		`apply_id` int(11) unsigned not null comment '应用ID',
		
		`ip` varchar(20) not null default '' comment '请求ip',
		
		`data` varchar(1000) not null default 0 comment '请求数据',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间',
		
		`create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间[数据创建时自动写入创建时间]',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key (`id`),
		
		key `mch_id`(`mch_id`),
		
		key `api_id`(`api_id`),
		
		key `mch_api_id`(`mch_api_id`),
		
		key `ip`(`ip`),
		
		key `create_time`(`create_time`)
	
	)engine=innodb default charset=utf8 comment='API请求记录'",
	
	//短信模板库
	'cen_sms_temp' => "CREATE TABLE cen_sms_temp(
	
		`id` int(11) unsigned not null auto_increment comment '主键',

		`type_id` int(11) unsigned not null comment '類型',
		
		`mch_id` int(11) unsigned not null default 0 comment '所属商户ID',
		
		`content` varchar(100) not null comment '模板内容',
		
		`status` tinyint(1) not null default 0 comment '状态',
		
		`default` tinyint(1) not null default 0 comment '默认',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间',
		
		`update_time` int(11) unsigned not null default 0 comment '修改时间',
		
		`create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间[数据创建时自动写入创建时间]',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		key `mch_id`(`mch_id`),
		
		key `create_time`(`create_time`)
	
	)engine=innodb auto_increment=1000 default charset=utf8 comment='短信模板库'",
	
	//短信模板類型
	'cen_sms_temp_type' => "CREATE TABLE cen_sms_temp_type(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`name` varchar(20) not null comment '類型名字',

		`create_time` int(11) unsigned not null default 0 comment '创建时间',
		
		`update_time` int(11) unsigned not null default 0 comment '修改时间',
		
		`create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间[数据创建时自动写入创建时间]',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		key `create_time`(`create_time`)

	)engine=innodb default charset=utf8 comment='短信模板類型'",
	
	//短信签名库
	'cen_sms_sign' => "CREATE TABLE cen_sms_sign(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`mch_id` int(11) unsigned not null default 0 comment '所属商户ID',
		
		`sign` varchar(10) not null comment '签名',
		
		`status` tinyint(1) not null default 0 comment '状态',
		
		`default` tinyint(1) not null default 0 comment '默认',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间',
		
		`update_time` int(11) unsigned not null default 0 comment '修改时间',
		
		`create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间[数据创建时自动写入创建时间]',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		key `mch_id`(`mch_id`),
		
		key `create_time`(`create_time`)
	
	)engine=innodb auto_increment=1000 default charset=utf8 comment='短信签名库'",
];

?>