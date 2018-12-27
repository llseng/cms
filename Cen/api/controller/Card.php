<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-22
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;

use \Db;
use app\api\logic as logics;
use app\common\logic as logic;

class Card extends Base
{

	//构造函数
	public function __construct()
	{
		
		//商户信息认证
		parent::__construct();
		
	}
	
	//验证身份证
	public function auth()
	{
		//POST请求
		$post = input("post.");
		//需要的数据
		$data = [
			'idcard' => $post['idcard'],
			'real_name' => $post['real_name'],
		];
		
		//记录
		$this->record($data);
		
		//验证器
		$result = logics\Card::authV($data);
		if( $result !== true )
		{
			return json(self::returnError($result,$data));
		}
		
		//认证信息
		$auth = logic\Card::auth($data['idcard'],$data['real_name']);
		//认证异常
		if( !$auth ) 
		{
			return json(self::returnError("认证异常",$data));
		}
		
		//认证失败
		if( !((int)$auto['status']) ) 
		{
			
			return json(self::returnError($auth['msg'] ?: '实名认证不通过',$data));
		}
		
		return json(self::returnSuccess(['data'=>$auth],'实名认证通过'));
	}
	
	//测试
	public function test()
	{
		
		$test = \app\common\logic\Card::auth('12345678912345678900','测试');
	
		var_dump($test);
	}

}