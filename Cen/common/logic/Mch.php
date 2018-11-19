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
use \Session;
use \Request;

class Mch
{
	//数据表名
	static public $dbName = "mch";
	
	//商户session作用域
	static public $sessArea = "mch";

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
	
	//获取商户信息 BY 手机号
	static public function getMchByphone($phone)
	{
		//条件
		$where = ['phone'=>$phone];
		
		$result = Db::name(self::$dbName)->field(self::$field)->where($where)->find();
		
		return $result ?: false;
		
	}
	
	//登录获取数据
	static public function getMchByLogin($user,$password)
	{
		//条件
		$where = "password='" . cen_md5($password) . "'";
		
		//登录名是手机格式
		if( is_phone($user) )
		{
			$result = Db::name(self::$dbName)->field(self::$field)->where("phone='{$user}' and " . $where)->find();
		}
		
		//商户昵称登录
		if( !$result){
			$result = Db::name(self::$dbName)->field(self::$field)->where("name='{$user}' and " . $where)->find();
		}
		
		return $result ?: false;
	}
	
	//记录商户登录信息
	static public function mchLoginOperation($mch_id)
	{
		//
		$update = [];
		
		//登录IP
		$update['last_ip'] = Request::ip();
		
		//登录时间
		$update['last_time'] = NOWTIME;
		
		$result = Db::name(self::$dbName)->where("id={$mch_id}")->update($update);
		
		return $result ? true : false;
	}
	
	//商户登录 (保存商户SESSION)
	static public function setLogin($mchData)
	{
		Session::set('user',$mchData,self::$sessArea);
		
		self::mchLoginOperation($mchData['mch_id']);
		
		return $mchData;
	}
	
	//商户登录数据 (商户登录SESSION数据)
	static public function getLogin()
	{
		$mchData = Session::get('user',self::$sessArea);
		
		return $mchData;
	}
	
	//商户推出 (清除商户SESSION)
	static public function loginClose()
	{
		Session::clear(self::$sessArea);
	}
	
	//注册商户
	static public function register(array $data)
	{
		//注册信息
		$mchData = [];
		
		$mchData['name'] = $data['name'];
		$mchData['nick'] = $data['nick'];
		$mchData['phone'] = $data['phone'];
		$mchData['password'] = cen_md5($data['password']);
		
		//创建商户获取商户ID
		$mch_id = self::create($mchData);
		if( !$mch_id ) return false;
		
		unset($mchData['password']);
		$mchData['mch_id'] = $mch_id;
		
		//返回商户信息
		return $mchData;
		
	}
	
	//创建应用
	static public function createApply(array $data)
	{
		//应用数据
		$applyData = [];

		//所属商户ID
		$applyData['mch_id'] = $data['mch_id'];
		
		//应用名
		$applyData['name'] = $data['name'];
		
		//应用昵称
		$applyData['nick'] = $data['nick'];
		
		//应用介绍
		$applyData['intro'] = $data['intro'];
		
		//应用ID
		$applyData['id'] = Apply::create($applyData);
		if( !$applyData ) return false;
		
		return $applyData;
	}

	//构造函数
	public function __construct()
	{
	
	
	}
	
}
