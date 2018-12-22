<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-22
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\api\logic;

use app\api\validate as validates;
use app\common\validate as validate;

class Card
{
	//构造函数
	public function __construct()
	{
		
	
	}
	
	//认证请求数据验证
	static public function requestV(array $data)
	{
		$validate = new validate\ApiRequest();

		$result = $validate->scene('request')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}
	

}