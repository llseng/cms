<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-22
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\api\controller;

use \Request;
use app\common\logic as logic;
use app\api\logic as logics;

/**
API 请求 基础类  存放所有API请求前置操作
	过滤 验证 前置 记录 ...
**/

class Base extends \Cencms\ApiBase
{
	//API信息
	protected $apiData;
	
	//商户API信息
	protected $mchApiData;
	
	//构造函数
	public function __construct()
	{
		
		//父级构造函数
		parent::__construct();
		
		/** API 服务 **/
		
		//设置API信息
		$this->setApiData( strtolower(CONTROLLER_NAME) );
		
		//API 是否存在
		if( !$this->apiData )
		{
			printJSON(self::returnError( logics\ApiError::getError('ERROR_API_NOT_EXIST') ));
		}
		
		//API 服务是否开启
		if( !$this->apiData['status'] )
		{
			printJSON(self::returnError( logics\ApiError::getError('ERROR_API_NOT_OPEN') ));
		}
		
		/** 商户数据验证 **/
		
		$post = input('post.');
		
		//商户API 请求数据过滤
		$result = logics\Base::requestV($post);
		if( $result !== true )
		{
			printJSON(self::returnError($result));
		}
		
		//商户API是否存在
		$mchApiSign = logic\MchApi::getMchApiSign($post['mch_api_id'], $post['mch_id']);
		if( !$mchApiSign )
		{
			printJSON(self::returnError( logics\ApiError::getError('ERROR_MCHAPI_NOT_EXIST') ));
		}
		
		////商户API  IP白名单过滤
		$this->ipwhiteFilter();
		
		//秘钥是否正确
		if( !$mchApiSign['sign'] || $post['sign'] != $mchApiSign['sign'] )
		{
			printJSON(self::returnError( logics\ApiError::getError('ERROR_MCHAPI_SIGN_ERROR') ));
		}
		
		//设置商户API信息
		$this->setMchApiData($post['mch_api_id'], $post['mch_id']);
		
		//商户API是否开启
		if( !$this->mchApiData['status'] )
		{
			printJSON(self::returnError( logics\ApiError::getError('ERROR_MCHAPI_NOT_OPEN') ));
		}

	}
	
	//设置API信息
	private function setApiData($api_name)
	{
		$apiData = logic\Api::getApiByName($api_name);
		
		$this->apiData = $apiData;
		
	}
	
	//设置商户API信息
	private function setMchApiData($mch_api_id, $mch_id)
	{
		$mchApiData = logic\MchApi::getMchApiById($mch_api_id, $mch_id);
		
		$this->mchApiData = $mchApiData;
	}
	
	//记录
	protected function record(array $data)
	{
		
		//API 请求记录
		$apiLog = [
			'mch_id' => $_POST['mch_id'],
			'mch_api_id' => $_POST['mch_api_id'],
			'api_id' => $this->mchApiData['api_id'],
			'apply_id' => $_POST['apply_id'] ?: '',
			'ip' => Request::ip(),
			'data' => json_encode($data),
		];
		
		//入库
		logic\ApiLog::create($apiLog);
		
	}
	
	//商户API  IP白名单过滤
	protected function ipwhiteFilter()
	{
		//商户API IP白名单开启列表
		$ipwhiteListData = logic\MchApiIpwhite::getMchApiIpwhiteOpenList($_POST['mch_api_id']);
		
		var_dump($ipwhiteListData);die;
	}

}
