<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-09 09:22:54
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Db;

class Phone
{
	//数据表名
	static public $dbName = "phone";
	
	//显示字段
	static public $field = "id,phone,company,province,city,areacode,zip,create_time";
	
	//创建记录
	static public function create(array $data)
	{
		//入库数据
		$insert = [];
		
		//手机号
		$insert['phone'] = $data['phone'];
		
		//运营商
		$insert['company'] = $data['company']?:'';
		
		//省份
		$insert['province'] = $data['province']?:'';
		
		//城市
		$insert['city'] = $data['city']?:'';
		
		//区号
		$insert['areacode'] = $data['areacode']?:'';
		
		//邮编
		$insert['zip'] = $data['zip']?:'';
		
		//邮编
		$insert['create_time'] = NOWTIME;

		$result = Db::name(self::$dbName)->insertGetId($insert);
		
		return $result ?: false;
	}
	
	//获取记录信息
	static public function getDataByPhone($phone)
	{
		//条件
		$where = [
			'phone' => $phone,
		];
		
		$result = Db::name(self::$dbName)->field(self::$field)->where($where)->find();
	
		return $result ?: false;
	
	}
	
	//获取手机信息  没有就创建
	static public function getPhoneData($phone)
	{
		//获取记录信息i
		$result = self::getDataByPhone($phone);
		
		//有直接返回
		if( $result )
		{
			return $result;
		}
		
		//手机信息
		$insert = [];
		$insert['phone'] = $phone;
		
		//获取手机附属地
		$addr = \Addons\Taobao\TelAddr::get($phone);
		if( is_string($addr) )
		{
			Logger::log([
				'phone' => $phone,
				'error' => $addr
			],__DIR__ . '/' . 'phone/' . date("Y-m/d") . '.log');
		}else{
			$insert = array_merge($insert,$addr);
		}
		
		//数据入库
		$insert['id'] = self::create($insert);
		//入库失败
		if( !$insert['id'] )
		{
			Logger::log([
				'phone' => $phone,
				'insert' => $insert,
				'error' => '入库失败',
			],__DIR__ . '/' . 'phone/' . date("Y-m/d") . '.log');
			return false;
		}
		
		return $insert;
		
	}

	//构造函数
	public function __construct()
	{
		
	}

}