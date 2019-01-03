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
// 
namespace app\admin\controller;

use app\common\logic as logic;
use app\admin\logic as logics;

class config extends Base
{
	
	//
	public function __construct()
	{
		//
		parent::__construct();

	}

	//主页
	public function index()
	{
		echo "Hello World";
	}

	//配置列表
	public function getList()
	{
		$result = logic\Config::get('');

		return json(self::returnSuccess(['list'=>$result],'获取成功'));
	}

	//设置站点配置
	public function set()
	{

		$post = input('post.');

		$data= [
			'name' => $post['name'],
			'value' => $post['value'],
			'intro' => $post['intro'],
		];

		//数据验证
		$result = logics\Config::setV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}

		//设置
		$res = logic\Config::set_config($data['name'], $data['value'], '', $data['intro']);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}

		return json(self::returnSuccess([],'操作成功'));
	}

}