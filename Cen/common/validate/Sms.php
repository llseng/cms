<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-08
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\validate;

use think\Validate;
use app\common\logic as logic;

class Sms extends Validate
{
	
	//验证规则
	public $rule = [
		//验证码
		'code' => ['require', 'regex' => "/^[0-9a-zA-Z]{4,6}$/"], 
		
		//手机号
		'phone' => ['require','length' => 11, 'regex' => "/^1[3|4|5|7|8]\d{9}$/", 'phoneTimeLimit' => 80]
		
	];
	
	//错误提示
	public $message = [
		//验证码
		'code.regex' => '验证码格式错误,仅支持英文数字4~6位',
		
		//手机号
		'phone.length' => '手机号长度错误',
		'phone.regex' => '手机号格式错误,请提交正确手机号',
	
	];
	
	//验证场景
	public $scene = [
		//发送验证短信(单条)
		'send' => ['code','phone'],
	];
	
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

}