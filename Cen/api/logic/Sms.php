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

namespace app\api\logic;

use app\api\validate as validates;
use app\common\validate as validate;

class Sms
{

	//构造函数
	public function __construct()
	{
	
	
	}
	
	//数据验证
	static public function sendV(array $data)
	{
		$validate = new validate\Sms();
		
		$result = $validate->scene('send')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}
	
	
}
