<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-08
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\api\logic as logics;
use app\common\logic as logic;

class Sms extends Base
{
	const API_NAME = 'sms';
	
	//构造函数
	public function __construct()
	{
		//父级构造函数
		parent::__construct();
		
		//商户API类型是否错误[ 是否是其他API ID 秘钥 ]
		if( !$this->mchApiVerify() )
		{
			printJSON(self::returnError( logics\ApiError::getError('ERROR_MCHAPI_APITYPE_ERROR') ));
		}
	}
	
	//是否是其他API 信息
	private function mchApiVerify()
	{
		//获取API类型
		$apiData = logic\Api::getApiById($this->mchApiData['api_id']);
		
		if( !$apiData || $apiData['name'] != static::API_NAME)
		{
			return false;
		}
		return true;
	}

	//发送验证短信(单条)
	public function send()
	{
		//POST提交
		$post = input('post.');
	
		$data = [
			'code' => $post['code'],
			'phone' => $post['phone'],
		];
		
		//记录
		$this->record($data);
		
		//验证
		$result = logics\Sms::sendV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//短信发送
		$send = 1;//logic\Sms::send($data['code'],$data['phone']);
		
		if( !$send ) 
		{
			return json(self::returnError('发送失败'));
		}
		
		return json(self::returnSuccess([],'发送成功'));
	
	}
	
	//

}