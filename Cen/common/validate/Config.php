<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2019-01-03
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------
namespace app\common\validate;

use think\Validate;
use app\common\logic as logic;

class Config extends Validate
{

	
	//验证规则
	public $rule = [
		//配置名
		'name' => ['require', 'regex' => "/^\w{3,20}$/"], 
		
		//配置值
		'value' => ['require', 'regex' => "/^\S{1,512}$/u"],
		
		//配置分组
		'group' => ['require', 'regex' => "/^\S{1,50}$/u"],
		
		//配置简介
		'intro' => [ 'regex' => "/^\S{4,50}$/u"],
		
	];
	
	//错误提示
	public $message = [
		//验证码
		'name.regex' => '配置名格式错误,仅支持英文数字下划线3~20位',
		
		//配置值
		'value.regex' => '配置值格式错误,仅支持1~512位非空字符',
	
		//配置分组
		'group.regex' => '配置分组格式错误,仅支持1~50位非空字符',
	
		//配置简介
		'intro.regex' => '配置简介格式错误,仅支持4~50位非空字符',
	];
	
	//验证场景
	public $scene = [
		//配置修改
		'set' => ['name','value','intro'],
	];
	
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