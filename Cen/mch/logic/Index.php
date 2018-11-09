<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-09 09:22:54
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\mch\logic;

use app\common\validate as validate;

class Index
{

	//构造函数
	public function __construct()
	{
		
		
		
	}
	
	//商户注册信息验证
	static public function regV(array $data)
	{
		//
		$validate = new validate\Mch();
		
		$result = $validate->scene('reg')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
		
	}
	
	//商户登录信息验证
	static public function loginV(array $data)
	{
		//
		$validate = new validate\Mch();
		
		$result = $validate->scene('login')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}
	
	//商户验证码数据验证
	static public function regPhoneCodeV(array $data)
	{
		//
		$validate = new validate\Sms();
		
		$result = $validate->scene('send')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}

}
