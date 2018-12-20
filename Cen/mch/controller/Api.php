<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-20 17:58:29
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\mch\controller;

use app\common\logic as logic;
use app\mch\logic as logics;

class Api extends Base
{
	//构造方法
	public function __construct()
	{
		//父级构造方法
		parent::__construct();
		
	}
	
	//主页
	public function index()
	{
		return json(self::returnError());
		
	}
	
	//创建API
	public function create()
	{
		//
		$id = (int)input('id');
		
		//api是否存在
		if( !( $id && $apiDat = logic\Api::getApiById($id) ) )
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		//创建
		$res = logic\MchApi::create($id, $mch_id);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess('操作成功'));
		
	}
	
}