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

/**
考虑到logic类许多方法一样 , 所以创建各基础类 . 方便之后创建的类公用
**/

use \Db;

class Base
{

	//数据表
	static public $dbName = "test";
	
	//显示字段
	static public $dbField = "id,name,nick,create_time,update_time,status";

	//获取数据表名
	static public function dbName()
	{
		return static::$dbName;
	}

	//获取数据显示
	static public function dbField()
	{
		//数组直接返回
		if( is_array(static::$dbField) )
		{
			return static::$dbField;
		}
		
		$field = explode(',',static::$dbField);
		
		return $field;
	}
	
	//获取字段可用条件
	static public function dbWhere()
	{
		//数组直接返回
		if( is_array(static::$dbField) )
		{
			return static::$dbField;
		}
		
		$where = explode(',',static::$dbField);
		
		return $where;
	}
	
	//插入数据
	static public function create(array $data)
	{
		
		//插入时间
		$data['create_time'] = NOWTIME;
		
		isset( $data['status'] ) && $data['status'] = $data['status'] ? 1: 0;
		
		//插入数据库
		$result = Db::name(static::dbName())->insertGetId($data);
		
		return $result ?: false;
	
	}
	
	//获取一条数据
	static public function get(array $where)
	{
		
		//
		$result = Db::name(static::dbName())->field(static::dbField())->where($where)->where('cancel',0)->find();
		
		return $result ?: false;
	}
	
	static public function getById($id)
	{
		//
		$where = [];
		$where['id'] = $id;
		
		return static::get($where);
		
	}
	
	//修改数据
	static public function set(array $where, array $data)
	{
		
		//修改时间
		$data['update_time'] = NOWTIME;
		
		//
		if( isset($data['cancel']) ) unset($data['cancel']);
		
		//状态
		isset($data['status']) && $data['status'] = $data['status'] ? 1 : 0;
		
		$result = Db::name(static::dbName())->where($where)->where('cancel',0)->update($data);
		
		return $result ?: false;
	}
	
	static public function setById($id, array $data)
	{
		//
		$where = [];
		$where['id'] = $id;
		
		return static::set($where,$data);
	}
	
	//删除数据
	static public function cancel(array $where)
	{
		$data = [
			'cancel' => 1,
		];
		
		$result = Db::name(static::dbName())->where($where)->where('cancel',0)->update($data);
		
		return $result ?: false;
	}

	static public function cancelById($id)
	{
		$where = ['id'=>$id];

		return static::cancel($where);
	}
	
	//获取列表
	static public function getList(array $where, $order = "create_time desc", $start = 0, $num = 20)
	{
		
        //可用条件
        $yes_where = static::dbWhere();

        //列表显示字段
        $list_field = static::dbField();
        //foreach($list_field as $key => &$val){$val = 'o.'.$val;}
        
        //显示字段
        $field = join(",",$list_field);
        //搜索条件
        $where_arr = [];

        //获取可用条件
        if( $where )
        {
            foreach( $where as $key => $val )
            {
				//跳过不可用条件
                if( !in_array($val[0],$yes_where) ) continue;
                //$val[0] = 'o.'.$val[0];
                $where_arr[] = $val;
            }
        }
		
		//获取
		$result = Db::name(static::dbName())->field($field)->where($where_arr)->where('cancel',0)->order($order)->limit($start,$num)->select();
		
		return $result ?: false;
		
	}
	
	//获取 关联 列表
	static public function xgetList(array $where, $order = "create_time desc", $start = 0, $num = 20)
	{
		//当前表别名
		$dbAlias = 'a';
		
		//关联表
		$joinDb = 'cen_mch b';
		//关联表条件
		$joinWhere = $dbAlias . ".mch_id = b.id";
		//关联表显示字段
		$joinField = "b.name as mch_name,b.nick as mch_nick";
		
        //可用条件
        $yes_where = static::dbWhere();		
		
        //列表显示字段
        $list_field = static::dbField();
		foreach($list_field as $key => &$val){$val = $dbAlias.'.'.$val;}
        
        //显示字段
        $field_str = join(",",$list_field) . ',' . $joinField;
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
		$result = Db::name(self::$dbName . " as " . $dbAlias)->field($field_str)->join($joinDb,$joinWhere)->where($where_arr)->where($dbAlias.'.cancel',0)->order($order)->limit($start,$num)->select();
		
		return $result ?: false;
		
	}

}