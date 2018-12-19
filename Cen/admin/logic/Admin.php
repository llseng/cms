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

namespace app\admin\logic;

use app\common\logic as logic;
use app\common\validate as validate;

class Admin extends logic\Admin
{
	
	//验证登录数据
	static public function loginV(array $data)
	{
		//
		$validate = new validate\Admin();
		
		$result = $validate->scene('login')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;

	}

	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
	
	}

	
}