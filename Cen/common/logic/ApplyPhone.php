<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-27
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

class ApplyPhone extends Base
{

	static public $dbName = 'apply_phone';
	
	static public $dbField = 'id,apply_id,apply_name,phone_id,phone,status,create_time,alter_time';

	//
	static public function getApplyPhone( $apply_id, $phone )
	{
		$where = [];
		$where['apply_id'] = $apply_id;
		$where['phone'] = $phone;
		
		return static::get($where);
	}
}