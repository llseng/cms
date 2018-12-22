<?php 
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-21
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\mch\logic;

use app\common\validate as validate;
use app\common\logic as logic;

class Api
{
	
	//设置API秘钥数据验证
	static public function setSignV(array $data)
	{
		$validate = new validate\MchApi();
		
		$result = $validate->scene('setSign')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}

	//
	static public function getSignPhoneCodeV(array $data)
	{
		$validate = new validate\Sms();
		
		$result = $validate->scene('send')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}

	//
	static public function setSignPhoneCodeV(array $data)
	{
		$validate = new validate\Sms();
		
		$result = $validate->scene('send')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}

	//
	static public function createApiIpwhiteV(array $data)
	{
		$validate = new validate\MchApi();
		
		$result = $validate->scene('createApiIpwhite')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}

	//
	static public function setApiIpwhiteV($id, $mch_api_id,array $data)
	{
		$validate = new validate\MchApi();
		
		$result = $validate->scene('setApiIpwhite')->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		//重复IP验证
		$where = [
			['id', 'not in', $id],
			['mch_api_id', '=', $mch_api_id],
			['ip', '=', $data['ip']],
		];
		$res = logic\MchApiIpwhite::get($where);
		if( $res )
		{
			return "IP {$data['ip']} 已存在,请换一个";
		}
		
		return true;
	}
	
	//

}