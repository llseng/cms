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
	
	//发送短信验证码
	static public function sendCode($code, $phone, $sign = false)
	{
		//请求参数
		$param = [
			'code' => $code,
		];
		
		
	}
	
	//模板短信发送
	static public function xsend($param, $phone, $temp_id, $sign = false)
	{
		
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
	
	//发送短信验证码
	/**
	$mark  string
	$data  array 
		phone  =>  接受手机号
		code   =>  发送验证码
	**/
	static public function sendPhoneCode($mark, array $data)
	{
		
		//
		$session = Session::get($mark);
		//间隔时间
		if($session && $data['phone'] == $session['phone'] && (NOWTIME - $session['create_time']) < $session['rule_time'] )
		{
			return "验证码已发送,请注意查收.(" . ($session['rule_time'] - (NOWTIME - $session['create_time']) ) ."秒后可再次发送验证码.)";
		}
		
		//发送验证码
		$send = self::send($data['code'],$data['phone']);
		if( !$send )
		{
			return false;
		}
		
		//创建时间
		$data['create_time'] = NOWTIME;
		//下次可发送间隔时间 \秒
		$data['rule_time'] = 120; 
		//验证码有效时间 \秒
		$data['eff_time'] = 600; 
		//保存数据
		Session::set($mark,$data);
		
		return '验证码发送成功,有效期为10分钟';
		
	}
	
	//验证短信验证码
	/**
	$mark  string
	$data  array 
		phone  =>  接受手机号
		code   =>  发送验证码
	**/
	static public function verifyPhoneCode($mark, array $data)
	{
		$session = Session::get($mark);
		//session 不存在 短信未发送
		if( !$session || !$data )
		{
			return false;
		}
		
		//间隔时间
		$gap = (NOWTIME - $session['create_time']);
		
		//验证码验证成功
		if( $data['phone'] == $session['phone'] && $data['code'] == $session['code'] && $gap < $session['eff_time'] )
		{
			//验证码只可使用一次
			Session::delete($mark);
			return true;
		}
		
		return false;
	}

	//构造函数
	public function __construct()
	{
		
	}
	
	
}