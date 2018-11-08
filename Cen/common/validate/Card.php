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

class Card extends Validate
{

	//验证规则
	public $rule = [
		//身份证号
		//'idcard' => ['require', 'length' => 18, 'regex' => "/^[0-9]{17}[0-9xX]$/"],
		'idcard' => ['require', 'length' => 18, 'regex' => "/^\d{6}[1-2]\d{3}((0[1-9])|(1[0-2]))(([0-2][1-9])|([1-3]0)|31)\d{3}[0-9Xx]$/"], //身份证号正则
		
		//正式姓名
		'real_name' => ['require', 'max' => 5, 'min' => 2, 'regex' => "/^[\x{4e00}-\x{9fa5}]{2,5}$/u"],
		
	];
	
	//错误提示
	public $message = [
		//身份证号
		'idcard.length' => '身份证号格式错误,身份证号为18位]',
		'idcard.regex' => '身份证号格式错误',
		
		//真实姓名
		'real_name.max' => '真实姓名超出最长限制',
		'real_name.min' => '真实姓名超出最短限制',
		'real_name.regex' => '真实姓名格式错误(仅支持中文)',
	
	];
	
	//验证场景
	public $scene = [
		//实名认证
		'auth' => ['idcard','real_name'],
	];

}