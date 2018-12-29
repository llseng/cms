<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-19
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------
namespace app\common\validate;

use think\Validate;
use app\common\logic as logic;

class Api extends Validate
{

	
	//验证规则
	public $rule = [
		//API名
		'name' => ['require', 'regex' => "/^\w{3,20}$/", 'nameExist'], 
		
		//API昵称
		'nick' => ['require', 'regex' => "/^\S{4,20}$/u"],
		
		//API简介
		'intro' => ['require', 'regex' => "/^\S{6,180}$/u"],
		
	];
	
	//错误提示
	public $message = [
		//验证码
		'name.regex' => 'API名格式错误,仅支持英文数字下划线3~20位',
		
		//API昵称
		'nick.regex' => 'API昵称格式错误,仅支持4~20位非空字符',
	
		//API简介
		'intro.regex' => 'API简介格式错误,仅支持6~180位非空字符',
	];
	
	//验证场景
	public $scene = [
		//API创建
		'create' => ['name','nick','intro'],
		//API修改
		'set' => ['nick','intro'],
		//设置秘钥
		'setApplySign' => ['sign'],
	];
	
	
	//API是否存在
	public function nameExist($value,$rule = '',$arr = [],$tab)
	{
		//获取API信息
		$result = logic\Api::getApiByName($value);
		
		if( !$result ) return true;
		
		return "API名 {$value} 已存在,请换一个.";
		
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