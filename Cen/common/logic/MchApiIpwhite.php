<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-21 16:12:14
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Db;

class MchApiIpwhite extends Base
{
	
	//数据表名
	static public $dbName = 'mch_api_ipwhite';
	
	//显示字段
	static public $dbField = 'mch_id,api_id,mch_api_id,ip,status,create_time,update_time';
	
	//创建IP白名单
	static public function create(array $data)
	{
		//
		$insert = [];
		
		//
		$insert['mch_id'] = $data['mch_id'];
		//
		$insert['api_id'] = $data['api_id'];
		//商户APIID
		$insert['mch_api_id'] = $data['mch_api_id'];
		
		//创建时间
		$insert['create_time'] = NOWTIME;
		
		$result = Db::name(static::dbName())->insertGetId($insert);
		
		return $result ?: false;
		
	}

	//构造函数
	public function __construct()
	{
		
		
	}
	
}