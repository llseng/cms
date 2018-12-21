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

class MchApi extends Validate
{

	
	//验证规则
	public $rule = [
		
		//API秘钥
		'sign' => ['require', 'regex' => "/^[0-9a-zA-Z]{32}$/"],
		
	];
	
	//错误提示
	public $message = [
		
		//应用秘钥
		'sign.regex' => 'API秘钥格式错误,仅支持英文数字32位',
	];
	
	//验证场景
	public $scene = [
		//设置秘钥
		'setSign' => ['sign'],
	];
	
	/*
	//商户名是否存在
	public function applyNameExist($value,$rule = '',$arr = [],$tab)
	{
		//获取应用信息
		$result = logic\Apply::getApplyByName($value);
		
		if( !$result ) return true;
		
		return "应用名{$value}已存在,请换一个.";
		
	}
	*/
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