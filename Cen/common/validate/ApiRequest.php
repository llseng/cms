<?php
namespace app\common\validate;

use think\Validate;
use app\common\logic as logic;

class ApiRequest extends Validate
{

	
	//验证规则
	public $rule = [
		//API ID 
		'mch_api_id' => ['require', 'integer'], 
		
		//商户 ID 
		'mch_id' => ['require', 'integer'], 
		
		//商户 应用 ID 
		'apply_id' => ['integer'], 
		
		//商户API秘钥 
		'sign' => ['require', 'regex' => "/^[0-9a-zA-Z]{32}$/"], 
		
	];
	
	//错误提示
	public $message = [
		//API ID
		'mch_api_id.integer' => 'API_ID格式错误,仅支持英文数字32位',
		
		//商户 ID 
		'mch_id.integer' => '商户ID格式错误',
		
		//商户 应用 ID 
		'apply_id.integer' => '商户应用ID格式错误',
	
		//商户API秘钥
		'sign.regex' => 'API秘钥格式错误',
	];
	
	//验证场景
	public $scene = [
		//API 请求
		'request' => ['mch_api_id','mch_id','apply_id','sign'],
	];
	
	/*
	//管理名是否存在
	public function applyExist($value,$rule = '',$arr = [],$tab)
	{
		//获取管理信息
		$result = logic\Admin::getByName($value);
		
		if( !$result ) return true;
		
		return "管理名 {$value} 已存在,请换一个.";
		
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