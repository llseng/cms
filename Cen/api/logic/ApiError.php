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

namespace app\api\logic;

use app\common\logic as logic;

class ApiError extends logic\Error
{
	//
	public function __construct()
	{
		parent::__construct();
	
		$this->add([
			//商户API不存在
			'ERROR_MCHAPI_NOT_EXIST' => 'API_ID错误',
			//商户API 秘钥错误
			'ERROR_MCHAPI_SIGN_ERROR' => 'API秘钥错误',
			//商户API 类型错误
			'ERROR_MCHAPI_APITYPE_ERROR' => 'API类型错误',
			//商户API未开启
			'ERROR_MCHAPI_NOT_OPEN' => 'API未开启',
			//API服务未开启
			'ERROR_API_NOT_OPEN' => 'API服务暂时关闭',
			//API服务不存在
			'ERROR_API_NOT_EXIST' => '无API服务',
			//IP过滤
			'ERROR_API_IP_FILTER' => 'IP限制',
			//应用不存在
			'ERROR_MCHAPPLY_ERROR' => '应用不存在',
		]);
	}
	
}
