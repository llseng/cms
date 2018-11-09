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
use Addons\Aliyun\SmsHelper;

class Sms
{
	//数据表名
	static public $dbName = "smslist";
	
	//创建数据
	static public function create(array $data)
	{
		//
		$insert = [];
		
		//第三方流水号
		$insert['biz_id'] = $data['biz_id'];
		
		$insert['mch_id'] = $data['mch_id'] ?: '';
		
		$insert['apply_id'] = $data['apply'] ?: '';
		
		$insert['phone'] = $data['phone'];
		
		$insert['params'] = $data['params'];
		
		$insert['product'] = $data['product'] ?: '';
		
		$insert['temp_id'] = $data['temp_id'] ?: '';
		
		$insert['create_time'] = NOWTIME;
		
		$result = Db::name(self::$dbName)->insert($insert);
		
		return $result ? $insert : false;
		
	}
	
	//获取手机号最后一次发送短信记录
	static public function getPhoneLastSms($phone)
	{
		//条件
		$where = [];
		
		$where['phone'] = $phone;
		
		$result = Db::name(self::$dbName)->where($where)->order("create_time desc")->find();
		
		return $result?:false;
	}
	
	//发收短信
	static public function send($code,$phone,$product = false,$tempcode = false)
	{
		//请求参数
		$param = [
			'code' => $code
		];
		
		//模板与标签得同时使用
		if( !$product || !$tempcode ) 
		{
			$result = SmsHelper::sendSms($param,$phone);
		}else{
			$result = SmsHelper::sendSms($param,$phone,$product,$tempcode);
		}
		
		//第三方请求失败
		if( is_string($result) )
		{
			Logger::log([
				'code' => $code,
				'phone' => $phone,
				'product' => $product,
				'tempcode' => $tempcode,
				'error' => $result
			],__DIR__ . '/' . 'Sms/' . date("Y-m/d") . '.log');
			
			return false;
		}
		
		//入库数据
		$insert = [];
		
		$insert['biz_id'] = $result['BizId'];
		
		$insert['mch_id'] = $_POST['mch_id'];
		
		$insert['apply_id'] = $_POST['apply_id'];
		
		$insert['phone'] = $phone;
		
		$insert['params'] = json_encode($param);
		
		$insert['product'] = $product;
		
		$insert['temp_id'] = $tempcode;
		
		//获取手机信息 (没有则创建)
		$phoneData = Phone::getPhoneData($phone);
		
		//数据入库
		$save = self::create($insert);
		//入库失败
		if( !$save )
		{
			Logger::log([
				'code' => $code,
				'phone' => $phone,
				'product' => $product,
				'tempcode' => $tempcode,
				'error' => '入库失败'
			],__DIR__ . '/' . 'Sms/' . date("Y-m/d") . '.log');
		}
		
		return true;
		
	}

	//构造函数
	public function __construct()
	{
		
	}
	
	
}