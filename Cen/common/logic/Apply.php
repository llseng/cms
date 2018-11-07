<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-07
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;


class Apply
{
	//构造函数
	public function __construct()
	{
	
	}
	
	//创建应用
	static public function create(array $data)
	{
		//应用信息
		$insert = [];
		
		//商户ID
		$insert['mch_id'] = $data['mch_id'];
		
		//应用名
		$insert['name'] = $data['name'];
		
		//应用昵称
		$insert['nick'] = $data['nick'];
		
		//应用简介
		$insert['intro'] = $data['intro'];
		
		//创建时间
		$insert['create_time'] = NOWTIME;
		
		return $insert;
		
	}

}