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

namespace app\admin\logic;

use app\common\logic as logic;
use app\common\validate as validate;

class Config
{

	//设置配置数据
	static public function setV(array $data)
	{
		//
		$validate = new validate\Config();
		
		$result = $validate->scene('set')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}
	
	//
	public function __construct()
	{


	}

}