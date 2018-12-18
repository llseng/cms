<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-19 17:24:47
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------
namespace app\common\validate;

use think\Validate;
use app\common\logic as logic;

class Apply extends Validate
{

	
	//验证规则
	public $rule = [
		//应用名
		'name' => ['require', 'regex' => "/^\w{6,20}$/", 'applyNameExist'], 
		
		//应用昵称
		'nick' => ['require', 'regex' => "/^\S{4,20}$/"],
		
		//应用简介
		'intro' => ['require', 'regex' => "/^\S{6,180}$/"],
		
		//应用秘钥
		'sign' => ['require', 'regex' => "/^[0-9a-zA-Z]{32}$/"],
		
	];
	
	//错误提示
	public $message = [
		//验证码
		'name.regex' => '应用名格式错误,仅支持英文数字下划线6~20位',
		
		//商户昵称
		'nick.regex' => '应用昵称格式错误,仅支持4~20位非空字符',
	
		//应用简介
		'intro.regex' => '应用简介格式错误,仅支持6~180位非空字符',
		
		//应用秘钥
		'sign.regex' => '应用秘钥格式错误,仅支持英文数字32位',
	];
	
	//验证场景
	public $scene = [
		//应用创建
		'create' => ['name','nick','intro'],
		//应用修改
		'set' => ['nick','intro'],
		//设置秘钥
		'setApplySign' => ['sign'],
	];
	
	
	//商户名是否存在
	public function applyNameExist($value,$rule = '',$arr = [],$tab)
	{
		//获取应用信息
		$result = logic\Apply::getApplyByName($value);
		
		if( !$result ) return true;
		
		return "应用名{$value}已存在,请换一个.";
		
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