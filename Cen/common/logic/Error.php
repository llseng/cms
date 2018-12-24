<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-24
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

class Error
{
	//
	public function __construct()
	{
		
	}
	
	//错误信息列表
	protected $ErrorList = [
	
		'ERROR_UNKNOWN' => '未知错误',
		
	];
	
	//添加错误信息
	public function add(array $data)
	{
		
		$error = array_merge($data, $this->ErrorList);
		
		$this->ErrorList = $error;
		
	}
	
	//获取错误信息
	public function get($key = false)
	{
		if( $key === false || (!is_int($key) && !is_string($key)) )
		{
			return $this->ErrorList['ERROR_UNKNOWN'];
		}
		
		return $this->ErrorList[$key] ?: $this->ErrorList['ERROR_UNKNOWN'];
	}
	
	//获取错误信息列表
	public function getList()
	{
		return $this->$ErrorList;
	}
	
	/**  **/
	
	static protected $init;
	
	static protected function getInit()
	{
		if( !static::$init )
		{
			static::$init = new static();
		}
		
		return static::$init;
	}

	static public function getError($key = false)
	{
		
		$init = static::getInit();
		
		return $init->get($key);
		
	}
	
}
