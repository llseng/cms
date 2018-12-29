<?php
namespace app\common\validate;

use think\Validate;
use app\common\logic as logic;

class Mch extends Validate
{

	
	//验证规则
	public $rule = [
		//商户名
		'name' => ['require', 'regex' => "/^\w{6,20}$/", 'mchNameExist'], 
		
		//手机号
		'phone' => ['require','length' => 11, 'regex' => "/^1[3|4|5|7|8]\d{9}$/"],
		
		//商户昵称
		'nick' => ['require', 'regex' => "/^\S{4,20}$/u"],
		
		//商户密码
		'password' => ['require', 'regex' => "/^\w{6,16}$/"],
		
		//商户登录名
		'user' => ['require', 'regex' => "/^\S{6,20}$/u"],
		
	];
	
	//错误提示
	public $message = [
		//验证码
		'name.regex' => '商户名格式错误,仅支持英文数字下划线6~20位',
		
		//手机号
		'phone.length' => '手机号长度错误',
		'phone.regex' => '手机号格式错误,请提交正确手机号',
		
		//商户昵称
		'nick.regex' => '商户昵称格式错误,仅支持4~20位非空字符',
		
		//商户密码
		'password.regex' => '密码格式错误,仅支持英文数字下划线6~16位',
	
		//商户登录名
		'user.regex' => '商户登录名格式错误,仅支持6~20位非空字符',
	];
	
	//验证场景
	public $scene = [
		//商户注册
		'reg' => ['name','phone','nick','password'],
		//商户登录
		'login' => ['user', 'password'],
	];
	
	
	//商户名是否存在
	public function mchNameExist($value,$rule = '',$arr = [],$tab)
	{
		//获取商户信息
		$result = logic\Mch::getMchByName($value);
		
		if( !$result ) return true;
		
		return "商户名{$value}已存在,请换一个.";
		
	}
	
	/*
	//手机短信时间限制
	public function phoneTimeLimit($value,$rule,$arr = [],$tab)
	{
		//手机最后一条短信记录
		$result = logic\Sms::getPhoneLastSms($value);
		
		if( !result ) return true;
		
		//时间间隔
		$gap = NOWTIME - $result['create_time'];
		//小于时间间隔
		if(  $gap < $rule )
		{
			return "同手机号短信发送手机间隔为{$rule}秒,请" . ($rule-$gap) ."秒后再次提交.";
		}
		
		return true;
		
	}
	*/

}