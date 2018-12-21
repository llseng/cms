<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-19
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Db;
use \Session;
use \Request;

class Admin
{
	
	//数据表
	static public $dbName = "admin";
	
	//管理员登录Session作用域
	static public $sessArea = "admin";
	
	//显示字段
	static public $field = "id,name,nick,phone,email,intro,last_ip,last_time,create_time,status";
	
	//创建管理员
	static public function create(array $data)
	{
		//管理员信息
		$insert = [];
		
		//名
		$insert['name'] = $data['name'];
		
		//昵称
		$insert['nick'] = $data['nick'];
		
		//密码
		$insert['pass'] = $data['pass'];
		
		//角色ID
		isset($data['role_id']) && $insert['role_id'] = $data['role_id'];
		
		//简介
		isset($data['intro']) && $insert['intro'] = $data['intro'];
		
		//手机号
		isset($data['phone']) && $insert['phone'] = $data['phone'];
		
		//邮箱
		isset($data['email']) && $insert['email'] = $data['email'];
		
		//创建时间
		$insert['create_time'] = NOWTIME;
		
		//创建IP
		$insert['create_ip'] = Request::ip();
		
		$result = Db::name(self::$dbName)->insertGetId($insert);
		
		return $result ?: false;
	}
	
	//获取列表
	static public function getList(array $where, $order = "create_time desc", $start = 0, $num = 20)
	{
		
        //可用条件
        $yes_where = ['id','name','nick','phone','email','status','create_time'];

        //列表显示字段
        $list_field = ['id', 'name', 'nick', 'phone', 'email', 'intro', 'create_time', 'status' ];
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
	
	//获取信息
	static public function get(array $where )
	{
		
		//获取
		$result = Db::name(self::$dbName)->field(self::$field)->where($where)->where('cancel',0)->find();
		
		return $result ?: false;
		
	}
	
	static public function getById( $id )
	{
		//条件
		$where = [
			'id' => (int)$id,
		];
		
		return self::get($where);
		
	}
	
	static public function getByName( $name )
	{
		//条件
		$where = [
			'id' => $name,
		];
		
		return self::get($where);
		
	}
	
	//管理名密码获取信息
	static public function getByLogin($username, $password)
	{
		//条件
		$where = [];
		$where['name'] = $username;
		$where['pass'] = cen_md5($password);
		
		return self::get($where);
		
	}
	
	//设置信息
	static public function set( array $where, array $data)
	{
		//是否有条件
		if( empty($where) ) return false;
		
		//信息
		$update = [];
		
		//昵称
		isset($data['nick']) && $update['nick'] = $data['nick'];
		
		//密码
		isset($data['pass']) && $update['pass'] = $data['pass'];
		
		//角色ID
		isset($data['role_id']) && $update['role_id'] = $data['role_id'];
		
		//简介
		isset($data['intro']) && $update['intro'] = $data['intro'];
		
		//手机号
		isset($data['phone']) && $update['phone'] = $data['phone'];
		
		//邮箱
		isset($data['email']) && $update['email'] = $data['email'];
		
		//最后登录IP
		isset($data['last_ip']) && $update['last_ip'] = $data['last_ip'];
		
		//最后登录时间
		isset($data['last_time']) && $update['last_time'] = $data['last_time'];
		
		//修改时间
		$update['update_time'] = NOWTIME;
		
		//设置
		$result = Db::name(self::$dbName)->where($where)->where('cancel',0)->update($update);
		
		return $result ?: false;
	}
	
	static public function setById( $id, array $data)
	{
		//条件
		$where = [
			'id' => (int)$id,
		];
		
		return self::set($where,$data);
	}
	
	static public function setByName( $name, array $data)
	{
		//条件
		$where = [
			'name' => (int)$name,
		];
		
		return self::set($where,$data);
	}
	
	//登录
	static public function login(array $data)
	{
		//获取管理员信息
		$result = self::getByLogin($data['username'],$data['password']);
		if( !$result ) return false;
		
		//设置登录SESSION
		self::setSession($result);
		
		//记录登录信息
		self::loginRecord();
		
		return true;
	}
	
	//记录登录信息
	static public function loginRecord( $id = false )
	{
		//
		$admin_id = (int)$id;
		
		//管理员id 不存在 记录当前登录管理员
		if( !$admin_id )
		{
			$adminData = self::getSession();
			$admin_id = $adminData['id'];
		}
		
		/**登录信息**/
		$update = [];
		//登录Ip
		$update['last_ip'] = Request::ip();
		//登录时间
		$update['last_time'] = NOWTIME;
		
		$result = self::setById($admin_id,$update);
	}
	
	//设置登录SESSION
	static public function setSession(array $adminData)
	{
		//设置
		Session::set('admin', $adminData, self::$sessArea);
		
		return $adminData;
	}
	
	//获取登录SESSION
	static public function getSession()
	{
		//session数据
		$adminData = Session::get('admin', self::$sessArea);
		
		return $adminData;
	}
	
	//清除登录SESSION
	static public function clearSession()
	{
		//清除作用域所有数据
		Session::clear(self::$sessArea);
		
		//清除登录SESSION
		Session::delete('admin', self::$sessArea);
	}
	
	
	/** ================================================== **/
	
	//管理员数据
	protected $Data;
	
	//构造函数
	public function __construct()
	{
		//设置管理员数据
		$this->Data = self::getSession();
		
	}
	
	//获取管理员数据
	public function getData()
	{
		return $this->Data;
	}
	
}