<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-24
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Db;

class ApiLog extends Base
{

	//
	static public $dbName = "api_log";

	//
	static public $dbField = "id,mch_id,api_id,mch_api_id,apply_id,ip,data,create_time,create_date";
	
	//商户API记录前 N 条 IP记录
	static public function mchApiTopThreeIpLog($mch_api_id)
	{
		//
		$where = [];
		$where[] = ['mch_api_id', '=', $mch_api_id];
		$where[] = ['create_date', 'like', date("Y-m-d").'%'];
		
		$result = Db::name(static::dbName())->field(static::dbField())->where($where)->limit(3)->group('ip')->order('id')->select();
		
		return $result;
	}

}