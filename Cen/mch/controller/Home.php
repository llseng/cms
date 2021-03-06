<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络
// +----------------------------------------------------------------------
// | Creation time 2018-11-07 15:54:38
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\mch\controller;

use app\common\logic as logic;

class Home extends Base
{
	//构造函数
	public function __construct()
	{
		//父级构造函数
		parent::__construct();
		
		
		
	}
	
	//主页
	public function index()
	{
		logic\MchNotice::push('LOGIN');
		
		return json(self::returnSuccess($this->user));
		
	}
	
}