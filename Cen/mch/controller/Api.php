<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-20 17:58:29
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\mch\controller;

use app\common\logic as logic;
use app\mch\logic as logics;

class Api extends Base
{
	//构造方法
	public function __construct()
	{
		//父级构造方法
		parent::__construct();
		
	}
	
	//主页
	public function index()
	{
		return json(self::returnError());
		
	}
	
	//获取商户APi列表
	public function getList()
	{
		$post = input('post.');
		
		//排序
		$order = 'create_time desc';
		
		//开始位置
		$pageStart = 0;

		//列表条数
		$pageNum = 20;
		
		//查询条件
		$whereArr = [];
		
		$whereArr[] = ['mch_id','=',$this->user['mch_id']];
		
		//自定义条件
		if( $post )
		{
			//列表条数
			if( $post['pageNum'] ) $pageNum = (int)$post['pageNum'];
			//页码
			if( $post['page'] )	$pageStart = ((int)$post['page'] - 1) * $pageNum;
			
		}
		
		//获取记录
		$List = logic\MchApi::getList($whereArr, $order, $pageStart, $pageNum);
		
		return json(self::returnSuccess(['list'=>$List],'获取成功'));
	}
	
	//获取可用Api列表
	public function getApiList()
	{
		
		$post = input('post.');
		
		//排序
		$order = 'create_time desc';
		
		//开始位置
		$pageStart = 0;

		//列表条数
		$pageNum = 20;
		
		//查询条件
		$whereArr = [];
		
		//自定义条件
		if( $post )
		{
			//列表条数
			if( $post['pageNum'] ) $pageNum = (int)$post['pageNum'];
			//页码
			if( $post['page'] )	$pageStart = ((int)$post['page'] - 1) * $pageNum;
			
		}
		
		//获取记录
		$List = logic\Api::getList($whereArr, $order, $pageStart, $pageNum);
		
		return json(self::returnSuccess(['list'=>$List],'获取成功'));
	}
	
	//创建API
	public function create()
	{
		//
		$apiId = (int)input('id');
		
		//api是否存在
		if( !( $apiId && $apiDat = logic\Api::getApiById($apiId) ) )
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		//是否已创建
		$mch_api = logic\MchApi::getMchApi($apiId, $this->user['mch_id']);
		if( $mch_api )
		{
			return json(self::returnError('API已创建'));
		}
		
		//创建
		$res = logic\MchApi::create($apiId, $this->user['mch_id']);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess(['id'=>$res],'操作成功'));
		
	}
	
	//开关API状态
	public function setStatus()
	{
		//
		$id = (int)input('id');
		
		//1.是否存在
		//$result = logic\Api::getApiById($id);
		if( !($id && $result = logic\MchApi::getMchApiById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		$data = [
			'status' => $result['status'] ? 0: 1,
		];
		
		$status = $result['status'] ? '关闭' : '开启';
		
		//设置
		$res = logic\MchApi::setById($id, $data);
		if( !$res )
		{
			return json(self::returnError( $status . '失败' ));
		}
		
		return json(self::returnSuccess([], $status . '成功' ));
	}
	
	//获取APi秘钥
	public function getSign()
	{
		//
		$id = (int)input('id');
		$post = input('post.');
		
		//验证码 验证
		if( !$id || !$this->getSignPhoneCodeVerify( $id, $post['code'] ) )
		{
			return json(self::returnError('短信验证码错误'));
		}

		//验证API 是否存在
		if( !($id && $result = logic\MchApi::getMchApiSign($id, $this->user['mch_id'] )) )
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		//未设置秘钥
		if( !$result['sign'] )
		{
			return json(self::returnError('请先设置API秘钥'));
		}
		
		return json(self::returnSuccess($result,'获取成功'));
	}
	
	//获取 '获取API秘钥' 验证码
	public function getSignPhoneCode()
	{
		//
		$id = (int)input('id');

		//是否存在API
		if( !($id && $result = logic\MchApi::getMchApiSign($id, $this->user['mch_id'] )) )
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		$data = [
			'phone' => $this->user['phone'],
			'code' => rand_string(6),
		];
		
		//数据验证
		$result = logics\Api::getSignPhoneCodeV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//发送手机验证码
		$res = logic\Sms::sendPhoneCode('getApiSign' . $id, $data);
		if( !$res )
		{
			return json(self::returnError('发送失败'));
		}
		
		return json(self::returnSuccess([],'发送成功'));
	}
	
	//验证 '获取API秘钥' 验证码
	private function getSignPhoneCodeVerify($id, $code)
	{
		//
		$data = [
			'phone' => $this->user['phone'],
			'code' => $code,
		];
		
		$result = logic\Sms::verifyPhoneCode('getApiSign' . $id, $data);
		
		return $result;
	}
	
	//设置API秘钥
	public function setSign()
	{
		//
		$id = (int)input('id');
		
		//
		$post = input('post.');
		
		//数据
		$data = [
			'sign' => $post['sign'],
		];
		
		//数据验证
		$result = logics\Api::setSignV($data);
		if( $result !== true)
		{
			return json(self::returnError($result));
		}
		
		//短信验证
		if( !$id || !$this->setSignPhoneCodeVerify($id, $post['code']) )
		{
			return json(self::returnError('短信验证码错误'));
		}
		
		//商户API验证是否存在
		if( !($id && $result = logic\MchApi::getMchApiSign($id, $this->user['mch_id'] )) )
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		//设置
		$res = logic\MchApi::setSignById($id, $data);
		if( !$res ){
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess([],'操作成功'));
	}
	
	//获取 `设置API秘钥` 短信验证码
	public function setSignPhoneCode()
	{
		//
		$id = (int)input('id');
		
		//商户API验证是否存在
		if( !($id && $result = logic\MchApi::getMchApiSign($id, $this->user['mch_id'] )) )
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		$data = [
			'phone' => $this->user['phone'],
			'code' => rand_string(6),
		];
		
		//数据验证
		$result = logics\Api::setSignPhoneCodeV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//发送手机验证码
		$res = logic\Sms::sendPhoneCode('setApiSign' . $id, $data);
		if( !$res )
		{
			return json(self::returnError('发送失败'));
		}
		
		return json(self::returnSuccess([],'发送成功'));
		
	}
	
	//验证 `设置API秘钥` 短信验证码
	private function setSignPhoneCodeVerify($id, $code)
	{
		//
		$data = [
			'phone' => $this->user['phone'],
			'code' => $code
		];
		
		$result = logic\Sms::verifyPhoneCode( 'setApiSign' . $id, $data);
		
		return $result;
	}
	
	/** API  IP 白名单 **/
	
	//添加IP白名单
	public function createApiIpwhite()
	{
		//商户API ID
		$mchApiId = input('id/d');
		
		$post = input('post.');
		
		//数据
		$data = [
			'ip' => $post['ip'],
		];
		
		//数据验证
		$result = logics\Api::createApiIpwhiteV($data);
		if( $result !== true ) 
		{
			return json(self::returnError($result));
		}
		
		//商户API ID 验证
		if( !($mchApiId && $mchApiData = logic\MchApi::getMchApiById($mchApiId, $this->user['mch_id']) ) )
		{
			return json(self::returnError('操作异常,API不存在'));
		}
		
		//是否已设置
		$result = logic\MchApiIpwhite::getMchApiIpwhiteByIp($data['ip'], $mchApiId);
		if( $result )
		{
			return json(self::returnError('IP白名单已设置'));
		}
		
		$data['mch_id'] = $mchApiData['mch_id'];
		$data['api_id'] = $mchApiData['api_id'];
		$data['mch_api_id'] = $mchApiId;
		
		//创建
		$res = logic\MchApiIpwhite::create($data);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess(['id'=>$res],'操作成功'));
		
	}
	
	//获取APIIP白名单列表
	public function getIpwhiteList()
	{
		//
		$mchApiId = input('id/d');
		$post = input('post.');
		
		//排序
		$order = 'create_time desc';
		
		//开始位置
		$pageStart = 0;

		//列表条数
		$pageNum = 20;
		
		//查询条件
		$whereArr = [];
		$whereArr[] = ['mch_id','=',$this->user['mch_id']];
		
		//mchApiId 是商户的API ID   只获取mchApiId API 的ip的白名单 
		if( $mchApiId && $result = logic\MchApi::getMchApiById($mchApiId, $this->user['mch_id']) )
		{
			$whereArr[] = ['mch_api_id','=',$mchApiId];
		}			
		
		//自定义条件
		if( $post )
		{
			//列表条数
			if( $post['pageNum'] ) $pageNum = (int)$post['pageNum'];
			//页码
			if( $post['page'] )	$pageStart = ((int)$post['page'] - 1) * $pageNum;
			//
			if( $post['ip'] ) 
			{
				//MYSql 模糊查询防注入  $cond = addcslashes($cond,"%_");
				$whereArr[] = ['ip','like','%'.addcslashes($post['ip'],"%_").'%'];
			}
		}
		
		//获取记录
		$List = logic\MchApiIpwhite::getList($whereArr, $order, $pageStart, $pageNum);
		
		return json(self::returnSuccess(['list'=>$List],'获取成功'));
		
	}
	
	//设置API IP白名单
	public function setApiIpwhite()
	{
		//白名单记录ID
		$id = input('id/d');
		
		$post = input('post.');
		
		$data = [
			'ip' => $post['ip'],
		];
		
		//白名单记录ID 验证
		if( !( $id && $mchApiIpwhiteData = logic\MchApiIpwhite::getMchApiIpwhiteById($id, $this->user['mch_id'])) )
		{
			return json(self::returnError('操作异常,IP白名单不存在'));
		}
		
		//数据验证
		$result = logics\Api::setApiIpwhiteV($id, $mchApiIpwhiteData['mch_api_id'], $data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//设置
		$res = logic\MchApiIpwhite::setById($id, $data);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess([],'操作成功'));
		
	}
	
	//启用|关闭 IP白名单
	public function serApiIpwhiteStatus()
	{
		//
		//白名单记录ID
		$id = input('id/d');
		
		//白名单记录ID 验证
		if( !( $id && $mchApiIpwhiteData = logic\MchApiIpwhite::getMchApiIpwhiteById($id, $this->user['mch_id'])) )
		{
			return json(self::returnError('操作异常,IP白名单不存在'));
		}
		
		//当前 启用状态
		$status = $mchApiIpwhiteData['status'];
		
		//设置后启用状态
		$updateStatus = $status ? 0: 1;
		
		//启用时查看是否超过 启用限制条数
		if( $updateStatus )
		{
			//当前 商户API 启用条数
			$mchApiIpwhiteOpenCount = logic\MchApiIpwhite::mchApiIpwhiteOpenCountByMchApiId($mchApiIpwhiteData['mch_api_id']);
			
			//启用限制条数
			$mchApiIpwhiteOpenLimit = logic\MchApiIpwhite::mchApiIpwhiteOpenLimit();
			
			// 是否 大于|等于 开启限制数
			if( $mchApiIpwhiteOpenCount >= $mchApiIpwhiteOpenLimit )
			{
				return json(self::returnError("API IP白名单超过了限制 ({$mchApiIpwhiteOpenLimit}条)")); 
			}
			
		}
		
		//
		$data = [
			'status'=>$updateStatus,
		];
		
		//
		$statusName = $updateStatus ? '启用': '关闭';
		
		//
		$res = logic\MchApiIpwhite::setById($id, $data);
		if( !$res )
		{
			return json(self::returnError( $statusName .'失败'));
		}
		
		return json(self::returnSuccess([], $statusName .'成功'));
		
	}
	
}