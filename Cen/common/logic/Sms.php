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
//use Addons\Aliyun\SmsHelper;
use Addons\Submail\Sms as SmsHelper;

class Sms
{
	//数据表名
	static public $dbName = "smslist";

	//验证码短信模板
	static public $verifyTemp = "您的验证码是@{code},有效期为@{time}分钟!";

	static public function getVerifyTemp()
	{
		return static::$verifyTemp;
	}

	//默认签名
	static public $defaultSign = "念菲网络";

	static public function getDefaultSign()
	{
		return static::$defaultSign;
	}
	
	//创建数据
	static public function create(array $data)
	{
		
		$data['send_id'] = $data['send_id'] ?: '';

		$data['mch_id'] = $data['mch_id'] ?: '';
		
		$data['apply_id'] = $data['apply_id'] ?: '';
		
		$data['temp_id'] = $data['temp_id'] ?: '';
		
		$data['create_time'] = NOWTIME;
		
		$result = Db::name(self::$dbName)->insertGetId($data);
		
		return $result ?: false;
		
	}

	//获取短息内容 
	/**
	 * $param  变量信息
	 * $temp   短息模板
	**/
	static public function getContent(array $param, $temp)
	{
		if( empty($param) ) return $temp;

		//查找值
		$replace = [];

		//替换值
		$subject = [];

		$i = 0;
		foreach ($param as $key => $value) {

			if( is_int($key) && $key == $i )
			{
				$replace[] = '@{' . ($key+1) . '}';
			}else{
				$replace[] = '@{' . $key . '}';
			}
			
			$subject[] = $value;

			$i++;
		}
//var_dump($replace, $subject, $temp);
		//模板变量替换
		$content = str_replace($replace, $subject, $temp);

		//去除无数据变量
		$content = preg_replace("/@\{([^\}]*?)\}/", '', $content);

		return $content;
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
	/**
	 * *
	 * @param  int $code  [验证码]
	 * @param  int $time  [有效时间]
	 * @param  string $phone [手机号]
	 * @return bool        [成功与否]
	 */
	static public function sendCode($code, $time, $phone, $mch_id = 0)
	{
		//请求参数
		$param = [
			'code' => $code,
			'time' => $time,
		];
		
		//获取默认 验证码短息模板
		$tempData = SmsTemp::getDefault(1,$mch_id);

		//不是站点默认 && 模板数据不存在
		if( (int)$mch_id !== 0 && empty($tempData) )
		{
			$tempData = SmsTemp::getDefault(1);
		}

		//模板数据不存在
		if( empty($tempData) )
		{
			$temp = static::getVerifyTemp();
		}else{
			$temp = $tempData['content'];
			$_POST['temp_id'] = $tempData['id'];
		}

		//获取默认签名
		$signData = SmsSign::getDefault($mch_id);

		//不是站点默认 && 签名数据不存在
		if( (int)$mch_id !== 0 && empty($signData) )
		{
			$signData = SmsSign::getDefault();
		}

		//签名数据不存在
		if( empty($signData) )
		{
			$sign = static::getDefaultSign();
		}else{
			$sign = $signData['sign'];
			$_POST['sign_id'] = $signData['id'];
		}

		return static::send($param, $phone, $temp, $sign);

	}
	
	//模板短信发送
	static public function xsend(array $param, $phone, $temp_id, $sign = false)
	{
		
	}

	//发送短息
	/**
	 * *
	 * @param  array   $param [变量数据]
	 * @param  string  $phone [接收手机号]
	 * @param  string  $temp  [模板]
	 * @param  string $sign  [签名]
	 * @return boolean         [成功与否]
	 */
	static public function send(array $param, $phone, $temp, $sign = '')
	{
		//短信内容
		$content = static::getContent($param, $temp);
//var_dump($phone, $content, $sign);die;
		//请求第三方发送短息
		$result = 1;//SmsHelper::sendSms($phone, $content, $sign);
		
		//第三方请求失败
		if( is_string($result) )
		{
			Logger::log([
				'param' => $param,
				'phone' => $phone,
				'temp' => $temp,
				'sign' => $sign,
				'error' => $result
			],__DIR__ . '/' . 'Sms/' . date("Y-m/d") . '.log');
			
			return false;
		}
		
		//入库数据
		$insert = [];
		
		$insert['send_id'] = $result['send_id'];
		
		$insert['mch_id'] = $_POST['mch_id'];
		
		$insert['apply_id'] = $_POST['apply_id'];
		
		$insert['phone'] = $phone;
		
		$insert['params'] = json_encode($param);
		
		$insert['sign'] = $sign;

		$insert['sign_id'] = $_POST['sign_id'];
		
		$insert['temp_id'] = $_POST['temp_id'];
		
		$insert['temp'] = $temp;
		
		//获取手机信息 (没有则创建)
		$phoneData = Phone::getPhoneData($phone);
		
		//数据入库
		$save = self::create($insert);
		//入库失败
		if( !$save )
		{
			Logger::log([
				'param' => $param,
				'phone' => $phone,
				'temp' => $temp,
				'sign' => $sign,
				'error' => '入库失败'
			],__DIR__ . '/' . 'Sms/' . date("Y-m/d") . '.log');
		}
		
		return true;

	}
	
	//发收短信 [初代 send方法]
	/*
	static public function send_1($code,$phone,$product = false,$tempcode = false)
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
	*/
	
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

		//有效时间
		$effI = 10;

		//发送验证码
		$send = static::sendCode($data['code'],$effI,$data['phone']);
		if( !$send )
		{
			return false;
		}
		
		//创建时间
		$data['create_time'] = NOWTIME;
		//下次可发送间隔时间 \秒
		$data['rule_time'] = 120; 
		//验证码有效时间 \秒
		$data['eff_time'] = $effI * 60; 
		//保存数据
		Session::set($mark,$data);
		
		return '验证码发送成功,有效期为'. $effI .'分钟';
		
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