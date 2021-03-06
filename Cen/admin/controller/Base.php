<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-18
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\logic as logics;
use app\common\logic as logic;

class Base extends \Cencms\ApiBase
{
	//管理对象
	protected $admin;

	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
		
		//设置管理对象
		$this->admin = new logics\Admin();
		
		//未登录
		if( empty($this->admin->getData()) )
		{
			
			printJSON(self::returnError('未登录,请先登录'));
			
		}
		
		
	}
	
	

}
