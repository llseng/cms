<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2019-01-02 
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

class SmsTempType extends Base
{
	
	//
	static public $dbName = 'sms_temp_type';

	//
	static public $dbField = 'id,name,create_time,create_date,update_time';

	//删除记录
	static public function cancel(array $where)
	{
		//不可删除 1 2 分类
		if( in_array($where['id'], [1,2]) ) return false;

		return parent::cancel($where);
	}

}