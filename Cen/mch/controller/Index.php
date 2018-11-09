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
		
		//
		$session = Session::get('phonecode');
		//间隔时间
		if($session && $data['phone'] == $session['phone'] && (NOWTIME - $session['create_time']) < $session['rule_time'] )
		{
			return json(self::returnError("验证码已发送,请注意查收.(" . ($session['rule_time'] - (NOWTIME - $session['create_time']) ) ."秒后可再次发送验证码.)"));
		}
		
		//数据验证
		$result = logics\Index::regPhoneCodeV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//发送验证码
		$send = logic\Sms::send($data['code'],$data['phone']);
		if( !$send )
		{
			return json(self::returnError('发送失败'));
		}
		
		//创建时间
		$data['create_time'] = NOWTIME;
		//下次可发送间隔时间 \秒
		$data['rule_time'] = 120; 
		//验证码有效时间 \秒
		$data['eff_time'] = 600; 
		//保存数据
		Session::set('phonecode',$data);
		
		return json(self::returnSuccess([],'验证码发送成功,有效期为10分钟'));
		
	}
	
	//验证商户注册短信验证码
	private function regPhoneCodeVerify($phone,$code)
	{
		$session = Session::get('phonecode');
		//session 不存在 短信未发送
		if( !$session )
		{
			return false;
		}
		
		//间隔时间
		$gap = (NOWTIME - $session['create_time']);
		
		//验证码验证成功
		if( $phone == $session['phone'] && $code == $session['code'] && $gap < $session['eff_time'] )
		{
			//验证码只可使用一次
			Session::delete('phonecode');
			return true;
		}
		
		return false;
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