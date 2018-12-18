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

class Api extends Base
{

	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
		
	}
	
	public function index()
	{
		
		return json(self::returnError());
		
	}

}
