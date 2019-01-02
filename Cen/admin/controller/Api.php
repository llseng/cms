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

use app\common\logic as logic;
use app\admin\logic as logics;

class Api extends Base
{

	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
		
	}
	
	//主页
	public function index()
	{
		
		var_dump($this->admin->getData());
		
		return json(self::returnError());
		
	}
	
	//获取API 列表
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
		
		//自定义条件
		if( $post )
		{
			//列表条数
			if( $post['pageNum'] ) $pageNum = (int)$post['pageNum'];
			//页码
			if( $post['page'] )	$pageStart = ((int)$post['page'] - 1) * $pageNum;
			
		}
		
		//获取记录
		$List = logic\Api::getList($whereArr, $order, $pageStart, $pageNum);
		
		return json(self::returnSuccess(['list'=>$List],'获取成功'));
	}
	
	//创建 API 
	public function create()
	{
		//
		$post = input('post.');
		
		//数据
		$data = [
			'name' => $post['name'],
			'nick' => $post['nick'],
			'intro' => $post['intro'],
		];
		
		//数据验证
		$result = logics\Api::createV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//
		$res = logic\Api::create($data);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess(['id'=>$res],'操作成功'));
	}
	
	//获取APi信息
	public function get()
	{
		//APi ID 
		$id = (int)input('id');
		
		//获取
		//$res = logic\Api::getApiById($id);
		if( !($id && $res = logic\Api::getApiById($id) ) ) 
		{
			return json(self::returnError('操作异常,API不存在'));
		}

		return json(self::returnSuccess(['data'=>$res],'获取成功'));
	}
	
	//设置API信息
	public function set()
	{
		//
		$id = (int)input('id');
		//
		$post = input('post.');
		
		//1.是否存在
		//$result = logic\Api::getApiById($id);
		if( !($id && $result = logic\Api::getApiById($id) ) ) 
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		//数据
		$data = [
			'name' => $post['name'],
			'nick' => $post['nick'],
			'intro' => $post['intro']
		];
		
		//2.数据验证
		$result = logics\Api::setV( $id, $data);
		if( $result !== true ){
			return json(self::returnError($result));
		}
		
		//设置
		$res = logic\Api::setApiById($id, $data);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess([],'操作成功'));
		
	}
	
	//开关API状态
	public function setStatus()
	{
		//
		$id = (int)input('id');
		
		//1.是否存在
		//$result = logic\Api::getApiById($id);
		if( !($id && $result = logic\Api::getApiById($id) ) )
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		$data = [
			'status' => $result['status'] ? 0: 1,
		];
		
		$status = $result['status'] ? '关闭' : '开启';
		
		//设置
		$res = logic\Api::setApiById($id, $data);
		if( !$res )
		{
			return json(self::returnError( $status . '失败' ));
		}
		
		return json(self::returnSuccess([], $status . '成功' ));
	}

}
