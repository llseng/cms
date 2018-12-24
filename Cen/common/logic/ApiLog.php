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

class ApiLog extends Base
{

	//
	static public $dbName = "api_log";

	//
	static public $dbField = "mch_id,api_id,mch_api_id,apply_id,ip,data,create_time";
	
}