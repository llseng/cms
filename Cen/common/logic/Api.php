<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-18
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Db;

class Api
{
	//数据表名
	static public $dbName = "api";
	
	//显示字段
	static public $field = "id,name,nick,intro,create_time,status";
	
	//创建接口
	static public function create(array $data)
	{
		//接口信息
		$insert = [];
		
		//接口名
		$insert['name'] = $data['name'];
		
		//接口昵称
		$insert['nick'] = $data['nick'];
		
		//接口简介
		$insert['intro'] = $data['intro'];
		
		//创建时间
		$insert['create_time'] = NOWTIME;
		
		$result = Db::name(self::$dbName)->insertGetId($insert);
		
		return $result ?: false;
		
	}
	
	//获取接口列表
	static public function getList(array $where, $order = "create_time desc", $start = 0, $num = 20)
	{
		
        //可用条件
        $yes_where = ['id','name','nick','status','create_time'];

        //列表显示字段
        $list_field = ['id', 'name', 'nick', 'intro', 'create_time', 'status' ];
        //foreach($list_field as $key => &$val){$val = 'o.'.$val;}
        
        //显示字段
        $field_str = join(",",$list_field);
        //搜索条件
        $where_arr = [];
		$where_arr[] = ['cancel','=',0];

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
		$result = Db::name(self::$dbName)->field($list_field)->where($where)->order($order)->limit($start,$num)->select();
		
		return $result ?: false;
		
	}
	
	//获取接口信息
	static public function getApi(array $where)
	{
		
		$result = Db::name(self::$dbName)->field(self::$field)->where($where)->where('cancel',0)->find();
		
		return $result ?: false;
		
	}
	
	static public function getApiById($id)
	{
		//条件
		$where = [];
		$where['id'] = $id;
		
		return self::getApi($where);
		
	}
	
	static public function getApiByName($name)
	{
		//条件
		$where = [];
		$where['name'] = $name;
		
		return self::getApi($where);
		
	}
	
	//设置接口信息
	static public function setApi(array $where, $data)
	{
		//信息
		$update = [];
		
		//接口昵称
		isset($data['nick']) && $update['nick'] = $data['nick'];
		
		//接口简介
		isset($data['intro']) && $update['intro'] = $data['intro'];
		
		//启用状态
		isset($data['status']) && $update['status'] = $data['status'] ? 1 : 0;
		
		//修改时间
		$update['update_time'] = NOWTIME;
		
		$result = Db::name(self::$dbName)->where($where)->where('cancel',0)->update($update);
		
		return $result ?: false;
	}
	
	static public function setApiById($id, $data)
	{
		//条件
		$where = [
			'id' => $id,
		];
		
		return self::setApi( $where, $data );
	}
	
	static public function setApiByName( $name, $data )
	{
		//条件
		$where = [
			'name' => $name,
		];
		
		return self::setApi( $where, $data );
	}

	//构造函数
	public function __construct()
	{
	
	}

}