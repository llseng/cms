<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-07
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Request;

class Mch
{

	//构造函数
	public function __construct()
	{
	
	
	}

	//创建商户
	static public function create(array $data)
	{
		//商户信息
		$insert = [];
		
		//商户名 (英文 数字 下划线)
		$insert['name'] = $data['name'];
		
		//商户昵称
		$insert['nick'] = $data['nick'];
		
		//手机号
		$insert['phone'] = $data['phone'];
		
		//登录密码
		$insert['password'] = $data['password'];
		
		//创建时间
		$insert['create_time'] = NOWTIME;
		
		return $insert;
		
	}
	
	//提交创建商户信息
	
	
}
