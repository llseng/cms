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

use \Db;

class MchApiIpwhite extends Base
{
	
	//数据表名
	static public $dbName = 'mch_api_ipwhite';
	
	//显示字段
	static public $dbField = 'id,mch_id,api_id,mch_api_id,ip,status,create_time,update_time';
	
	//获取列表
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
	
	//===
	
	//是否已设置IP白名单
	static public function getMchApiIpwhiteByIp($ip, $mch_api_id)
	{
		//条件
		$where = [];
		$where['ip'] = $ip;
		$where['mch_api_id'] = $mch_api_id;
		
		return static::get($where);
	}
	
	//获取商户API ip白名单 记录
	static public function getMchApiIpwhiteById($id, $mch_id)
	{
		//条件
		$where = [];
		$where['id'] = $id;
		$where['mch_id'] = $mch_id;
		
		return static::get($where);
	}
	
	//===
	
	//商户 API IP 白名单限制数
	static public $mchApiIpwhiteOpenLimit = 3;
	
	//获取 商户 API  IP 白名单限制数
	static public function getMchApiIpwhiteOpenLimit()
	{
		return static::$mchApiIpwhiteOpenLimit;
	}
	
	static public function mchApiIpwhiteOpenLimit($count = false)
	{
		if( $count !== false )
		{
			return static::getMchApiIpwhiteOpenLimit() <= (int)$count;
		}
		return static::getMchApiIpwhiteOpenLimit();
	}
	
	//===
	
	//商户API IP白名单开启数量
	static public function mchApiIpwhiteOpenCount(array $where)
	{
		//
		$result = Db::name(static::dbName())->where($where)->where(['status'=>1,'cancel'=>0])->count();
		
		return $result;
	}
	
	static public function mchApiIpwhiteOpenCountByMchApiId($mch_api_id)
	{
		$where = [
			'mch_api_id' => $mch_api_id,
		];
		
		return static::mchApiIpwhiteOpenCount($where);
	}
	
	//构造函数
	public function __construct()
	{
		
		
	}
	
}