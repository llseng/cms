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
		
		//排序
		$order = 'create_time desc';
		
		//开始位置
		$pageStart = 0;

		//列表条数
		$pageNum = 20;
		
		//查询条件
		$whereArr = [];
		$whereArr[] = ['mch_id','=',$this->user['mch_id']];
		
		//自定义条件
		if( $post )
		{
			//列表条数
			if( $post['pageNum'] ) $pageNum = (int)$post['pageNum'];
			//页码
			if( $post['page'] )	$pageStart = ((int)$post['page'] - 1) * $pageNum;
			
		}
		
		//获取记录
		$List = logic\Apply::getList($whereArr, $order, $pageStart, $pageNum);
		
		return json(self::returnSuccess(['list'=>$List],'获取成功'));
		
	}
	
	//设置应用信息
	public function setApply()
	{
		$post = input('post');
		
		//数据
		$data = [
			'name' => $post['name'],
			'nick' => $post['nick'],
			'intro' => $post['intro'],
			'status' => $post['status'],
			'cancel' => $post['cancel'],
		];
		
		//数据验证
		
		
		return json(self::returnSuccess([],'操作成功'));
	}

}