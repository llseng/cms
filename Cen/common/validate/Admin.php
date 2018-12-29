<?php
namespace app\common\validate;

use think\Validate;
use app\common\logic as logic;

class Admin extends Validate
{

	
	//验证规则
	public $rule = [
		//管理名
		'name' => ['require', 'regex' => "/^\w{5,20}$/", 'nameExist'], 
		
		//管理登录名
		'username' => ['require', 'regex' => "/^\S{5,20}$/u"],
		
		//手机号
		'phone' => ['length' => 11, 'regex' => "/^1[3|4|5|7|8]\d{9}$/"],
		
		//管理昵称
		'nick' => ['require', 'regex' => "/^\S{4,20}$/u"],
		
		//管理简介
		'intro' => [ 'regex' => "/^\S{6,255}$/u"],
		
		//管理密码
		'password' => ['require', 'regex' => "/^\w{6,16}$/"],
		
	];
	
	//错误提示
	public $message = [
		//管理名
		'name.regex' => '管理名格式错误,仅支持英文数字下划线5~20位',
		
		//手机号
		'phone.length' => '手机号长度错误',
		'phone.regex' => '手机号格式错误,请提交正确手机号',
		
		//管理昵称
		'nick.regex' => '管理昵称格式错误,仅支持4~20位非空字符',
	
		//管理简介
		'intro.regex' => '应用简介格式错误,仅支持6~255位非空字符',
		
		//管理密码
		'password.regex' => '密码格式错误,仅支持英文数字下划线6~16位',
	
		//管理登录名
		'username.regex' => '管理登录名格式错误,仅支持5~20位非空字符',
	];
	
	//验证场景
	public $scene = [
		//管理注册
		'reg' => ['name','phone','nick','password'],
		//管理登录
		'login' => ['username', 'password'],
	];
	
	
	//管理名是否存在
	public function nameExist($value,$rule = '',$arr = [],$tab)
	{
		//获取管理信息
		$result = logic\Admin::getByName($value);
		
		if( !$result ) return true;
		
		return "管理名 {$value} 已存在,请换一个.";
		
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