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

namespace app\admin\logic;

use app\common\logic as logic;
use app\common\validate as validate;

class Sms
{
	
	//验证创建模板数据
	static public function createTempV(array $data)
	{
		//
		$validate = new validate\Sms();
		
		$result = $validate->scene('createTemp')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		//商户
		if( $data['mch_id'])
		{
			$mchData = logic\Mch::getById($data['mch_id']);
			if( !$mchData )
			{
				return '商户不存在';
			}
		}
		
		//模板
		$temp = logic\SmsTemp::getByContent($data['content'], $data['mch_id']);
		if( $temp )
		{
			return "已存在相同模板,无需重复创建";
		}
		
		return true;

	}
	
	//验证创建模板数据
	static public function createSignV(array $data)
	{
		//
		$validate = new validate\Sms();
		
		$result = $validate->scene('createSign')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		//商户
		if( $data['mch_id'])
		{
			$mchData = logic\Mch::getById($data['mch_id']);
			if( !$mchData )
			{
				return '商户不存在';
			}
		}
		
		//签名
		$temp = logic\SmsSign::getBySign($data['sign'], $data['mch_id']);
		if( $temp )
		{
			return "已存在相同模板,无需重复创建";
		}
		
		return true;

	}
	
	//验证设置模板数据
	static public function setTempV($id,array $data)
	{
		//
		$validate = new validate\Sms();
		
		$result = $validate->scene('setTemp')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		//商户
		if( $data['mch_id'])
		{
			$mchData = logic\Mch::getById($data['mch_id']);
			if( !$mchData )
			{
				return '商户不存在';
			}
		}
		
		//重名验证
		$where = [
			['id','not in',$id],
			['content','=',$data['content']],
			['mch_id','=',$data['mch_id']],
		];
		$res = logic\SmsTemp::get($where);
		if( $res )
		{
			return "已存在相同模板,无需重复创建";
		}
		
		return true;

	}
	
	//验证设置签名数据
	static public function setSignV($id,array $data)
	{
		//
		$validate = new validate\Sms();
		
		$result = $validate->scene('setSign')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		//商户
		if( $data['mch_id'])
		{
			$mchData = logic\Mch::ById($data['mch_id']);
			if( !$mchData )
			{
				return '商户不存在';
			}
		}
		
		//重名验证
		$where = [
			['id','not in',$id],
			['sign','=',$data['sign']],
			['mch_id','=',$data['mch_id']],
		];
		$res = logic\SmsSign::get($where);
		if( $res )
		{
			return "已存在相同模板,无需重复创建";
		}
		
		return true;

	}
	
	//设置模板默认
	static public function setTempDefault(array $data)
	{
		//默认状态
		$default = $data['default'] ? 0: 1;
		
		//条件
		$where = [
			'mch_id' => $data['mch_id'],
		];
		
		//清空原有默认项
		if( $default ) logic\SmsTemp::set($where,['default'=>0]);
		
		//
		$update = [
			'status' => 1,
			'default' => $default,
		];

		//设置默认
		$result = logic\SmsTemp::setById($data['id'],$update);
		
		return $result ?: false;
	}
	
	//设置签名默认
	static public function setSignDefault(array $data)
	{
		//默认状态
		$default = $data['default'] ? 0: 1;
		
		//条件
		$where = [
			'mch_id' => $data['mch_id'],
		];
		
		//清空原有默认项
		if( $default ) logic\SmsSign::set($where,['default'=>0]);
		
		//
		$update = [
			'status' => 1,
			'default' => $default,
		];

		//设置默认
		$result = logic\SmsSign::setById($data['id'],$update);
		
		return $result ?: false;
	}

	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
	
	}

	
}