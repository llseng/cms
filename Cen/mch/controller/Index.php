<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-07 16:05:53
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------
namespace app\mch\controller;

use \Session;
use app\mch\logic as logics;
use app\common\logic as logic;

class Index extends \Cencms\ApiBase
{
	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
	
	}
	
	//首页
	public function index()
	{
		return json(self::returnError());
	}
	
	//商户注册
	public function register()
	{
		$post = input('post.');
		
		//验证短信验证码
		if( !$this->regPhoneCodeVerify($post['phone'],$post['code']) )
		{
			return json(self::returnError('短信验证码错误'));
		}

		//数据
		$data = [
			'name' => $post['name'],
			'nick' => $post['nick'],
			'phone' => $post['phone'],
			'password' => $post['password'],
		];
		
		//验证
		$result = logics\Index::regV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//注册商户
		$mchData = logic\Mch::register($data);
		if( !$mchData )
		{
			return json(self::returnError("注册失败请稍后再试"));
		}
		
		//注册后直接登录
		if( true )
		{
			//登录并保存商户信息
			logic\Mch::setLogin($mchData);
		}
		
		return json(self::returnSuccess($mchData),'注册成功');
		
	}
	
	//商户注册短信验证码
	public function regPhoneCode()
	{
		$post = input('post.');
		
		//数据
		$data = [
			'phone' => $post['phone'],
			'code' => rand_string(6), //随机验证码
		];
		
		//数据验证
		$result = logics\Index::regPhoneCodeV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//发送手机验证码
		$res = logic\Sms::sendPhoneCode('regMch',$data);
		if( !$res )
		{
			return json(self::returnError('发送失败'));
		}

		return json(self::returnSuccess([],$res));
		
	}
	
	//验证商户注册短信验证码
	private function regPhoneCodeVerify($phone,$code)
	{
		//数据
		$data = [
			'phone' => $phone,
			'code' => $code,
		];
		
		//验证手机验证码
		$result = logic\Sms::verifyPhoneCode('regMch',$data);
		
		return $result;
	}
	
	//商户登录
	public function login()
	{
		$post = input('post.');
		
		//数据
		$data = [
			'user' => $post['user'],
			'password' => $post['password']
		];
		
		//数据验证
		$result = logics\Index::loginV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//获取商户信息
		$mchData = logic\Mch::getMchByLogin($data['user'],$data['password']);
		
		if( !$mchData ) 
		{
			return json(self::returnError('登录名或密码错误'));
		}
		
		//登录并保存商户信息
		logic\Mch::setLogin($mchData);
		
		return json(self::returnSuccess($mchData,'登录成功'));
	}

}