<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-09 09:22:54
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\mch\logic;

use app\common\validate as validate;
use app\common\logic as logic;

class Apply
{

	//构造函数
	public function __construct()
	{
		
		
		
	}
	
	//商户创建应用信息验证
	static public function createV(array $data)
	{
		//
		$validate = new validate\Apply();
		
		$result = $validate->scene('create')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
		
	}
	
	//商户修改应用信息验证
	static public function setV(array $data)
	{
		//
		$validate = new validate\Apply();
		
		$result = $validate->scene('set')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
		
	}
	
	//应用是否是当前商户的
	static public function isMchApply($id, $mch_id)
	{
		//条件
		$where = [];
		$where['id'] = (int)$id;
		$where['mch_id'] = (int)$mch_id;
		
		//获取应用信息
		$result = logic\Apply::getApply($where);
		
		return $result;
	}
	
	//设置应用秘钥手机,验证码 数据验证
	static public function setApplySignPhoneCodeV(array $data)
	{
		$validate = new validate\Sms();
		
		$result = $validate->scene('send')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}
	
	//设置秘钥 数据验证
	static public function setApplySignV(array $data)
	{
		$validate = new validate\Apply();
		
		$result = $validate->scene('setApplySign')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}
	
}
