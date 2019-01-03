<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-29 13:07:03
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Db;

class SmsTemp extends Base
{
	//
	static public $dbName = 'sms_temp';
	
	//
	static public $dbField = 'id,mch_id,type_id,content,status,update_time,create_time,create_date,default';
	
	
	//获取列表
	static public function getList(array $where, $order = "create_time desc", $start = 0, $num = 20)
	{
		//当前表别名
		$dbAlias = 'a';
		
		//关联表
		$joinDb = 'cen_mch b';
		//关联表条件
		$joinWhere = $dbAlias . ".mch_id = b.id";
		//关联表显示字段
		$joinField = "b.name as mch_name,b.nick as mch_nick";

		//关联表2
		$joinDb_2 = 'cen_sms_temp_type c';
		//关联表条件
		$joinWhere_2 = $dbAlias . ".type_id = c.id";
		//关联表显示字段
		$joinField_2 = "c.name as type";
		
        //可用条件
        $yes_where = static::dbWhere();		
		
        //列表显示字段
        $list_field = static::dbField();
		foreach($list_field as $key => &$val){$val = $dbAlias.'.'.$val;}
        
        //显示字段
        $field_str = join(",",$list_field) . ',' . $joinField . ',' . $joinField_2;
        //搜索条件
        $where_arr = [];

        //获取可用条件
        if( $where )
        {
            foreach( $where as $key => $val )
            {
				//跳过不可用条件
                if( !in_array($val[0],$yes_where) ) continue;
                $val[0] = $dbAlias.'.'.$val[0];
                $where_arr[] = $val;
            }
        }
		
		//获取
		$result = Db::name(self::$dbName . " as " . $dbAlias)->field($field_str)->leftJoin($joinDb,$joinWhere)->leftJoin($joinDb_2,$joinWhere_2)->where($where_arr)->where($dbAlias.'.cancel',0)->order($order)->limit($start,$num)->select();
		
		return $result ?: false;
		
	}
	
	//获取同名模板
	static public function getByContent($content, $mch_id = 0)
	{
		//
		$where = [
			'content' => $content,
			'mch_id' => (int)$mch_id,
		];
		
		return static::get($where);
	}

	//获取默认模板
	/**
	 * *
	 * @param  integer  $type_id [分类ID]
	 * @param  integer $mch_id  [商户ID]
	 * @return array           [mysql数据]
	 */
	static public function getDefault($type_id, $mch_id = 0)
	{
		$where = [
			'default' => 1,
			'type_id' => (int)$type_id,
			'mch_id'  => (int)$mch_id,
		];

		return static::get($where);
	}

	//获取商户模板
	static public function getMchTempById($id, $mch_id)
	{
		$where = [
			'id' => $id,
			'status' => 1,
			'mch_id' => $mch_id,
		];

		return static::get($where);
	}
	
}