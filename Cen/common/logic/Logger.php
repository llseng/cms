<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-08 09:58:01
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Request;
use \Cencms\LtLogger;

class Logger
{
	
	//记录日志
	static public function log(array $data,$log_file)
	{
		if( empty($data) ) return false;
		
		$data['ip'] = Request::ip();
		
		$data['date'] = date("Y-m-d H:i:s");
		
		$data['post'] = $_POST;
		
		$msg = [];
		
		foreach( $data as $key => $val )
		{
			$msg[] = "[ {$key} ] : " . var_export($val,1);
		}
		
		//$logger = new LtLogger(__DIR__ . '/' . 'Card/' . date("Y-m/d") . '.log');
		$logger = new LtLogger($log_file);
		
		$logger->log($msg);
	}

}
