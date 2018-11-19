<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-19
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------
namespace app\mch\controller;

use app\mch\logic as logics;
use app\common\logic as logic;

class Apply extends Base
{

	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
		
	}
	
	//首页
	public function index()
	{
		//返回展示数据
		return json(self::returnError());
	}
	
	//创建应用
	public function create()
	{
		$post = input('post.');
		
		//必要数据
		$data = [
			'name' => $post['name'],
			'nick' => $post['nick'],
			'intro' => $post['intro'],
			'mch_id' => $this->user['mch_id'],
		];
		
		//数据验证
		$result = logics\Apply::createV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//创建应用
		$applyData = logic\Mch::createApply($data);
		if( !$applyData ) 
		{
			return json(self::returnError("创建失败"));
		}
		
		return json(self::returnSuccess($applyData,'创建成功'));
	}
	
	public function getList()
	{
		$post = input('post.');
		
		//查询条件
		$whereArr = [];
		$whereArr[] = ['mch_id','=',$this->user['mch_id']];
		
		return json(self::returnSuccess(['list'=>[]],'获取成功'));
		
	}

}