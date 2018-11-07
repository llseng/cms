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
	
	//商户列表
	'cen_mch' => "create table cen_mch(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`name` varchar(20) not null comment '用户名',
		
		`nick` varchar(20) not null comment '昵称',
		
		`phone` varchar(12) not null comment '手机号',
		
		`email` varchar(64) not null default '' comment '邮箱',
		
		`password` varchar(32) not null comment '登录密码',
		
		`paypwd` varchar(32) not null comment '支付密码',
		
		`codekey` varchar(32) not null comment '接口秘钥',
		
		`create_time` int(11) unsigned not null default 0 comment '创建时间搓',
		
		`cancel` tinyint(1) unsigned not null default 0 comment '撤销|删除',
		
		primary key(`id`),
		
		unique key `name`(`name`),
		
		key `create_time`(`create_time`)
		
	)engine=innodb default charset=utf8 auto_increment=1000 comment='商户列表'",
	
	//应用列表
	'cen_apply' => "create table cen_apply(
	
		`id` int(11) unsigned not null auto_increment comment '主键',
		
		`mch_id` int(11) unsigned not null comment '商户ID(外键)',
		
		`name` varchar(20) not null comment '应用名',
		
		`nick` varchar(20) not null comment '昵称',
		
		`intro` varchar(200) not null comment '应用简介',
		
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
	'cen_phone' => "create table cen_phone(
	
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
	
	//身份证信息列表
	'cen_card' => "create table cen_card(
	
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
	'cen_apply_phone' => "create table cen_apply_phone(
	
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
	
];

?>