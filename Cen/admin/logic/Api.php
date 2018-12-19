<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-19
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\logic;

use app\common\logic as logic;
use app\common\validate as validate;

class Api
{
	
	//验证创建数据
	static public function createV(array $data)
	{
		//
		$validate = new validate\Api();
		
		$result = $validate->scene('create')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;

	}
	
	//验证设置数据
	static public function setV($id,array $data)
	{
		//
		$validate = new validate\Api();
		
		$result = $validate->scene('set')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		//格式验证
		if( !preg_match("/^\w{3,20}$/",$data['name']) )
		{
			return $validate->message['name.regex'];
		}
		//重名验证
		$where = [
			['id','not in',$id],
			['name','=',$data['name']],
		];
		$res = logic\Api::getApi($where);
		if( $res )
		{
			return "API名 {$data['name']} 已存在,请换一个.";
		}
		
		return true;

	}

	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
	
	}

	
}