<?php

namespace app\common\logic;

use \Db;

class MchApi
{
	
	//数据表名
	static public $dbName = 'mch_api';
	
	//显示字段
	static public $field = 'mch_id,api_id,amount,static,create_time';
	
	//创建商户API
	static public function create($id, $mch_id)
	{
		//
		$insert = [];
		
		$insert['api_id'] = $id;
		
		$insert['mch_id'] = $mch_id;
		
		$insert['create_time'] = NOWTIME;
		
		//随机秘钥
		$insert['sign'] = rand_string(32,62);
		
		$result = Db::name(self::$dbName)->insertGetId($insert);
		
		return $result ?: false;
	}
	
	//获取接口列表
	static public function getList(array $where, $order = "create_time desc", $start = 0, $num = 20)
	{
		//当前表别名
		$dbAlias = 'a';
		
		//关联表
		$joinDb = 'cen_api b';
		//关联表条件
		$joinWhere = $dbAlias . ".api_id = b.id";
		//关联表显示字段
		$joinField = "b.name as api_name,b.nick as api_nick";
		
        //可用条件
        $yes_where = ['api_id','mch_id','status','create_time'];		
		
        //列表显示字段
        $list_field = ['id', 'api_id', 'mch_id', 'amount', 'create_time', 'status' ];
		foreach($list_field as $key => &$val){$val = $dbAlias.'.'.$val;}
        
        //显示字段
        $field_str = join(",",$list_field) . ',' . $joinField;
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
                $val[0] = $dbAlias.'.'.$val[0];
                $where_arr[] = $val;
            }
        }
		
		//获取
		$result = Db::name(self::$dbName . " as " . $dbAlias)->field($field_str)->join($joinDb,$joinWhere)->where($where)->order($order)->limit($start,$num)->select();
		
		return $result ?: false;
		
	}
	
	//获取接口信息
	static public function get(array $where)
	{
		//
		$result = Db::name(self::$dbName)->where($where)->where('cancel',0)->find();
		
		return $result ?: false;
	}
	
	static public function getById($id)
	{
		//
		$where = [];
		$where['id'] = $id;
		
		return self::get($where);
		
	}
	
	static public function getMchApi($api_id, $mch_id)
	{
		//
		$where = [];
		$where['api_id'] = $api_id;
		$where['mch_id'] = $mch_id;
		
		return self::get($where);
	}
	
	//获取API秘钥
	static public function getSign(array $where)
	{
		//
		$result = Db::name(self::$dbName)->field('sign')->where($where)->where('cancel',0)->find();
		
		return $result ?: false;
	}
	
	static public function getSignById($id)
	{
		//条件
		$where = [
			'id' => $id,
		];
		
		return self::getSign($where);
		
	}
	
	static public function getMchApiSign($id, $mch_id)
	{
		$where = [];
		$where['id'] = $id;
		$where['mch_id'] = $mch_id;
		
		return self::getSign($where);
		
	}
	
	//设置
	static public function set(array $where, array $data)
	{
		//
		$update = [];
		
		//使用额度
		//isset($data['amount']) && $update['amount'] = (int)$data['amount'];
		
		//启用状态
		isset($data['status']) && $update['status'] = (int)$data['status'];
		
		//秘钥
		isset($data['sign']) && $update['sign'] = $data['sign'];
		
		//修改时间
		$data['update_time'] = NOWTIME;
		
		$result = Db::name(self::$dbName)->where($where)->where('cancel',0)->update($update);
	
		return $result ?: false;
	}
	
	static public function setById($id, array $data)
	{
		//
		$where = [
			'id' => $id,
		];
		
		return self::set($where,$data);
	}
	
	//设置APi秘钥
	static public function setSign(array $where, array $data)
	{
		//数据
		if( !$data['sign'] ) return false;
		
		$update = [
			'sign' => $data['sign'],
		];
		
		return self::set($where,$update);
		
	}
	
	static public function setSignById($id, array $data)
	{	
		$where = [
			'id' => $id,
		];
		
		return self::setSign($where,$data);
		
	}
	
	
	
	//构造方法
	public function __construct()
	{
	
	
	
	}


}