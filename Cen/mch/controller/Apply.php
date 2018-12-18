<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-19
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------
namespace app\mch\controller;

use app\mch\logic as logics;
use app\common\logic as logic;

class Apply extends Base
{

	//构造函数
	public function __construct()
	{
		//执行父级构造函数
		parent::__construct();
		
	}
	
	//首页
	public function index()
	{
		//返回展示数据
		return json(self::returnError());
	}
	
	//创建应用
	public function create()
	{
		$post = input('post.');
		
		//必要数据
		$data = [
			'name' => $post['name'],
			'nick' => $post['nick'],
			'intro' => $post['intro'],
			//商户ID
			'mch_id' => $this->user['mch_id'],
		];
		
		//数据验证
		$result = logics\Apply::createV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//创建应用
		$applyData = logic\Mch::createApply($data);
		if( !$applyData ) 
		{
			return json(self::returnError("创建失败"));
		}
		
		return json(self::returnSuccess($applyData,'创建成功'));
	}
	
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
		$List = logic\Apply::getList($whereArr, $order, $pageStart, $pageNum);
		
		return json(self::returnSuccess(['list'=>$List],'获取成功'));
		
	}
	
	//获取应用信息
	public function getApply()
	{
		//应用ID
		$id = (int)input('id');
		
		//应用ID验证
		$result = logics\Apply::isMchApply($id, $this->user['mch_id']);
		if( !($id && $result) ) 
		{
			return json(self::returnError('操作异常,应用不存在'));
		}
		
		return json(self::returnSuccess($result,'操作成功'));
		
	}
	
	//设置应用信息
	public function setApply()
	{
		//要设置的ID
		$id = (int)input('id');
		
		//应用ID验证
		$result = logics\Apply::isMchApply($id, $this->user['mch_id']);
		if( !($id && $result) ) 
		{
			return json(self::returnError('操作异常,应用不存在'));
		}
		
		$post = input('post.');
		
		//设置数据
		$data = [
			//'name' => $post['name'],
			'nick' => $post['nick'],
			'intro' => $post['intro'],
		];
		
		isset($post['status']) && $data['status'] = $post['status'];
		isset($post['cancel']) && $data['cancel'] = $post['cancel'];
		
		//数据验证
		$result = logics\Apply::setV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//设置应用信息
		$res = logic\Apply::setApplyById($id, $data);
		if( !$res ) return json(self::returnError('操作失败'));
		
		return json(self::returnSuccess([],'操作成功'));
	}
	
	//设置应用秘钥
	public function setApplySign()
	{
		
		$post = input('post.');
		
		//应用ID
		$id = (int)input('id');
		
		//验证手机验证码
		if( !$this->setApplySignPhoneCodeVerify($id, $post['code']) )
		{
			return json(self::returnError('短信验证码错误'));
		}
		
		//应用ID验证
		$result = logics\Apply::isMchApply($id, $this->user['mch_id']);
		if( !($id && $result) ) 
		{
			return json(self::returnError('操作异常,应用不存在'));
		}
		
		//数据
		$data = [
			'sign' => $post['sign'],
		];
		
		//数据验证
		$result = logics\Apply::setApplySignV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//设置应用秘钥
		$res = logic\Apply::setApplySignById($id,$data);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess([],'操作成功'));
	}
	
	//获取设置秘钥手机验证码
	public function setApplySignPhoneCode()
	{
		
		$id = (int)input('id');
		
		//应用ID验证
		$result = $id && logics\Apply::isMchApply($id, $this->user['mch_id']);
		if( !$result ) 
		{
			return json(self::returnError('操作异常,应用不存在'));
		}
		
		$data = [
			'phone' => $this->user['phone'],
			'code' => rand_string(6),
		];
		
		//数据验证
		$result = logics\Apply::setApplySignPhoneCodeV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//发送手机验证码
		$res = logic\Sms::sendPhoneCode('setApplySign'.$id,$data);
		if( !$res )
		{
			return json(self::returnError('发送失败'));
		}
		
		return json(self::returnSuccess([],$res));
		
	}
	
	//验证设置秘钥手机验证码
	private function setApplySignPhoneCodeVerify($id, $code)
	{
		$data = [
			'phone' => $this->user['phone'],
			'code' => $code,
		];
		
		$result = logic\Sms::verifyPhoneCode('setApplySign'.$id,$data);
		
		return $result;
		
	}

}