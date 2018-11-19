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

class Apply
{

	//构造函数
	public function __construct()
	{
		
		
		
	}
	
	//商户注册信息验证
	static public function createV(array $data)
	{
		//
		$validate = new validate\Apply();
		
		$result = $validate->scene('create')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
		
	}
	
	
}
