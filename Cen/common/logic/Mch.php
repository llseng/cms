<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-07
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Db;
use \Request;

class Mch
{
	//数据表名
	static public $dbName = "mch";

	//创建商户 (返回数据自增ID)
	static public function create(array $data)
	{
		//商户信息
		$insert = [];
		
		//商户名 (英文 数字 下划线)
		$insert['name'] = $data['name'];
		
		//商户昵称
		$insert['nick'] = $data['nick'];
		
		//手机号
		$insert['phone'] = $data['phone'];
		
		//登录密码
		$insert['password'] = $data['password'];
		
		//创建时间
		$insert['create_time'] = NOWTIME;
		
		//创建IP
		$insert['create_ip'] = Request::ip();
		
		$result = Db::name(self::$dbName)->insertGetId($insert);
		
		return $result ?: false;
		
	}
	
	//商户显示字段
	static public $field = "id as mch_id,name,nick,phone,last_ip,last_time";
	
	//获取商户信息 BY 商户名
	static public function getMchByName($name)
	{
		//条件
		$where = ['name'=>$name];
		
		$result = Db::name(self::$dbName)->field(self::$field)->where($where)->find();
		
		return $result ?: false;
		
	}
	
	//注册商户
	static public function register(array $data)
	{
		//注册信息
		$mchData = [];
		
		$mchData['name'] = $data['name'];
		$mchData['nick'] = $data['nick'];
		$mchData['phone'] = $data['phone'];
		$mchData['password'] = my_md5($data['password']);
		
		//创建商户获取商户ID
		$mch_id = self::create($mchData);
		if( !$mch_id ) return false;
		
		unset($mchData['password']);
		$mchData['mch_id'] = $mch_id;
		
		//返回商户信息
		return $mchData;
		
	}

	//构造函数
	public function __construct()
	{
	
	
	}
	
}
