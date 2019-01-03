<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-28
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace Addons\Submail;

//短信公用类
require_once( __DIR__ . "/./lib/message.php");

class Sms
{
	//服务器地址
	public $server = 'https://api.mysubmail.com/';

	//短信配置信息
	public $config = [
		'appid' => '30615',
		'appkey' => 'd4d2da5e076d6c1a39bdebdb44b527dc',
		'sign_type' => 'md5',
		'server' => '',
	];

	//构造函数
	public function __construct()
	{
		$this->config['server'] = $this->server;
		
	}
	
	//短信发送实例
	public function getSend()
	{
		require_once( __DIR__ . '/./lib/messagesend.php');
		
		//短信发送实例
		$MessageSend = new \MESSAGEsend($this->config);
		
		return $MessageSend;
	}
	
	//获取默认签名
	public function getSign()
	{
		return '念菲网络';
	}
	
	//发送短信
	/**
		成功返回
			array(5) {
			  ["status"] => string(7) "success" 状态
			  ["send_id"] => string(32) "83ac2df7806e5b8c1bae4d00f82e8040" 请求ID
			  ["fee"] => int(1) 使用条数
			  ["sms_credits"] => string(2) "37" 剩余额度
			  ["transactional_sms_credits"] => string(1) "0" ?? 
			}
		失败返回
			{
				"status":"error", 状态
				"code":"1xx", 状态码
				"msg":"error message" 错误信息
			}
	**/
	public function send($phone, $content, $sign = false)
	{
		//短信发送实例
		$message = $this->getSend();
		
		//设置短信接收人
		$message->SetTo($phone);
		
		//短信内容
		if( empty($sign) )
		{
			$sign = $this->getSign();
		}
		
		$messageContent = '【'. $sign .'】' . $content;
		
		$message->SetContent($messageContent);
		
		$result = $message->send();
		
		//请求失败
		if( $result['status'] != "success" )
		{
			//状态码 错误信息
			return '接口请求异常 : ' . $result['code'] . '-' . $result['msg'];
		}
		
		return $result;
	}


	/** ========== **/

	//
	static $Instance;
	
	static public function init()
	{
		if( !self::$Instance )
		{
			self::$Instance = new self();
		}
		
		return self::$Instance;
	}
	
	static public function sendSms($phone, $content, $sign = false)
	{
		$init = static::init();

		return $init->send($phone, $content, $sign);
	}

}