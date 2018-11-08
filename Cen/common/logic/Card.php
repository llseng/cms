<?php
namespace app\common\logic;

use \Db;
use \Addons\Aliyun as Aliyuns;

class Card 
{
	//数据表名
	static public $dbName = "card";
	
	//创建记录
	static public function create(array $data)
	{
	
		//记录
		$insert = [];
		//身份证号
		$insert['idcard'] = $data['idcard'];
		//真实姓名
		$insert['real_name'] = $data['real_name'];
		//认证状态
		$insert['status'] = $data['status'] ? 1 : 0;
		//认证提示信息
		$insert['msg'] = $data['msg'];
		
		if( $insert['status'] )
		{
			//性别
			$insert['sex'] = $data['sex'] == 1 ? 1 : 2;
			
			//身份证所在地
			$insert['addr'] = $data['addr'];
			
			//地区编码
			$insert['addr_code'] = $data['addr_code'];
			
			//出生年月
			$insert['birth'] = $data['birth'];
		}
		
		//创建时间
		$insert['create_time'] = NOWTIME;
		
		//数据入库
		$result = Db::name(self::$dbName)->insert($insert);
	
		return $result ? $insert : false;
	}
	
	//显示字段
	static public $field = "idcard,real_name,status,sex,addr,addr_code,birth,msg";
	
	//获取验证记录
	static public function getCard($idcard,$realName)
	{
		//条件
		$where = [];
		$where['idcard'] = $idcard;
		$where['real_name'] = $realName;
		$where['cancel'] = 0;
		
		$result = Db::name(self::$dbName)->field(self::$field)->where($where)->find();
		
		return $result;
	}
	
	//认证
	static public function auth($idcard,$realName)
	{
		
		//获取本地记录
		$localCard = self::getCard($idcard,$realName);
		
		//本地有就不用去请求第三方了
		if( $localCard ) return $localCard;
		
		//第三方获取
		$requestCard = Aliyuns\IdcardAuth::auth($idcard,$realName);
		
		//第三方响应错误
		if( is_string($requestCard) ) 
		{
			//记录日志
			Logger::log([
				'idcard' => $idcard,
				'real_name' => $realName,
				'error' => $requestCard,
			],__DIR__ . '/' . 'Card/' . date("Y-m/d") . '.log');
			
			return false;
		}
		
		//将响应数据存入本地
		$result = self::create($requestCard);
		//保存失败
		if( !$result ) return false;
		
		//返回认证数据
		return $result;
		
	}
	
	//构造函数
	public function __construct()
	{
	
	
	}

}