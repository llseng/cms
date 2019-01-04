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

use \Db;

class Apply
{
	//数据表名
	static public $dbName = "apply";
	
	//显示字段
	static public $field = 'id,mch_id,name,nick,intro,create_time,status';
	
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
		
		$result = Db::name(self::$dbName)->insertGetId($insert);
		
		return $result ?: false;
		
	}
	
	//获取应用信息
	static public function getApply(array $where)
	{
		$where['cancel'] = 0;
		
		//获取
		$result = Db::name(self::$dbName)->field(self::$field)->where($where)->find();
		
		return $result ?: false;
		
	}
	
	static public function getApplyByName($name)
	{
		//条件
		$where = [];
		$where['name'] = $name;
		
		return self::getApply($where);
	}
	
	static public function getApplyById($id)
	{
		//条件
		$where = [];
		$where['id'] = $id;
		
		return self::getApply($where);
	}
	
	static public function getMchApplyById($id, $mch_id)
	{
		//
		$where = [];
		$where['id'] = $id;
		$where['mch_id'] = $mch_id;
		
		return self::getApply($where);
	}
	
	//获取应用列表
	static public function getList(array $where, $order = "create_time desc", $start = 0, $num = 20)
	{
		
        //可用条件
        $yes_where = ['id','mch_id','name','nick','status','create_time'];

        //列表显示字段
        $list_field = ['id', 'mch_id', 'name', 'nick', 'intro', 'create_time', 'status' ];
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
		$result = Db::name(self::$dbName)->field($list_field)->where($where_arr)->order($order)->limit($start,$num)->select();
		
		return $result ?: false;
		
	}
	
	//设置应用信息
	static public function setApply(array $where,array $data )
	{
		//设置的信息
		$update = [];
		//名
		//$data['name'] && $update['name'] = $data['name'];
		//昵称
		$data['nick'] && $update['nick'] = $data['nick'];
		//简介
		$data['intro'] && $update['intro'] = $data['intro'];
		//启用状态
		isset($data['status']) && $update['status'] = $data['status'] ? 1 : 0;
		//撤销
		//isset($data['cancel']) && $update['cancel'] = $data['cancel'] ? 1 : 0;
		$update['update_time'] = NOWTIME;
		
		$result = Db::name(self::$dbName)->where($where)->update($update);
		
		return $result ?: false;
	}
	
	static public function setApplyById( $id, $data)
	{
		$where = ['id' => $id];
		
		return self::setApply( $where, $data );
	}
	
	static public function setApplyByName( $name, $data)
	{
		$where = ['name' => $name];
		
		return self::setApply( $where, $data );
	}
	
	//设置应用秘钥
	static public function setApplySign(array $where, array $data)
	{
		
		$result = Db::name(self::$dbName)->where($where)->update($data);
		
		return $result ?: false;
	}
	
	static public function setApplySignById($id, array $data)
	{
		//条件
		$where = [
			'id' => $id,
		];
		
		return self::setApplySign($where, $data);
	}
	
	//构造函数
	public function __construct()
	{
	
	}
	

}