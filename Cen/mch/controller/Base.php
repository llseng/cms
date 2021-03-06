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

namespace app\mch\controller;

use app\common\logic as logic;

class Base extends \Cencms\ApiBase
{
	
	//商户信息
	protected $user;

	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
		
		//获取商户信息
		$this->user = logic\Mch::getLogin();
		//商户未登录
		if( !$this->user )
		{
			printJSON( self::returnError('未登录',['login'=>'NotLoggedIn']) );
		}
		
	}

}
