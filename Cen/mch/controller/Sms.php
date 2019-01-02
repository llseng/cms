<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2019-01-02 
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------
// 
namespace app\mch\controller;

use app\common\logic as logic;
use app\mch\logic as logics;
use app\admin\logic as adminLogic;

class Sms extends Base
{
	
	//
	public function __construct()
	{
		parent::__construct();
	}

	//获取模板分类
	public function getTempTypeList()
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
		//$whereArr[] = ['mch_id','=',$this->user['mch_id']];
		
		//自定义条件
		if( $post )
		{
			//列表条数
			if( $post['pageNum'] ) $pageNum = (int)$post['pageNum'];
			//页码
			if( $post['page'] )	$pageStart = ((int)$post['page'] - 1) * $pageNum;
			
		}
		
		//获取记录
		$List = logic\SmsTempType::getList($whereArr, $order, $pageStart, $pageNum);
		
		return json(self::returnSuccess(['list'=>$List],'获取成功'));
	}

	//获取模板列表
	public function getTempList()
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
		$List = logic\SmsTemp::getList($whereArr, $order, $pageStart, $pageNum);
		
		return json(self::returnSuccess(['list'=>$List],'获取成功'));
	}

	//创建 短信模板
	public function createTemp()
	{
		//
		$post = input('post.');
		
		//数据
		$data = [
			'content' => $post['content'],
			'type_id' => (int)$post['type_id'],
			'mch_id' => $this->user['mch_id'],
		];
		
		//数据验证
		$result = logics\Sms::createTempV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//
		$res = logic\SmsTemp::create($data);
		if( !$res )
		{
			return json(self::returnError('操作失败',$data));
		}
		
		return json(self::returnSuccess(['id'=>$res],'操作成功'));
	}

	//获取模板信息
	public function getTemp()
	{
		//APi ID 
		$id = (int)input('id');
		
		//获取
		//$res = logic\Api::getApiById($id);
		if( !($id && $res = logic\SmsTemp::getMchTempById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,模板不存在'));
		}

		return json(self::returnSuccess(['data'=>$res],'获取成功'));
	}

	//设置模板信息
	public function setTemp()
	{

		//
		$id = (int)input('id');
		//
		$post = input('post.');
		
		//1.是否存在
		//$result = logic\Api::getApiById($id);
		if( !($id && $tempData = logic\SmsTemp::getMchTempById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,模板不存在'));
		}
		
		//数据
		$data = [
			'content' => $post['content'],
			'type_id' => (int)$post['type_id'],
			'mch_id' => $this->user['mch_id'],
		];
		
		//2.数据验证
		$result = logics\Sms::setTempV( $id, $data);
		if( $result !== true ){
			return json(self::returnError($result));
		}

		//默认模板不可修改分类
		if( $tempData['default'] && $tempData['type_id'] != $data['type_id'] )
		{
			return json(self::returnError('当前为默认模板,不可修改分类'));
		}
		
		//设置
		$res = logic\SmsTemp::setById($id, $data);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess([],'操作成功'));
		
	}
	
	//开关模板状态
	public function setTempStatus()
	{
		//
		$id = (int)input('id');
		
		//1.是否存在
		//$result = logic\Api::getApiById($id);
		if( !($id && $result = logic\SmsTemp::getMchTempById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,模板不存在'));
		}
		
		$data = [
			'status' => $result['status'] ? 0: 1,
		];
		
		$status = $result['status'] ? '关闭' : '开启';
		
		//设置
		$res = logic\SmsTemp::setById($id, $data);
		if( !$res )
		{
			return json(self::returnError( $status . '失败' ));
		}
		
		return json(self::returnSuccess([], $status . '成功' ));
	}

	//删除模板
	public function deleteTemp()
	{
		//
		$id = (int)input('id');
		
		//1.是否存在
		if( !($id && $result = logic\SmsTemp::getMchTempById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,模板不存在'));
		}

		//设置
		$res = logic\SmsTemp::cancelById($id);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess([],'操作成功'));
	}

	//获取 短信签名 列表
	public function getSignList()
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
		$List = logic\SmsSign::getList($whereArr, $order, $pageStart, $pageNum);
		
		return json(self::returnSuccess(['list'=>$List],'获取成功'));
	}
	
	//创建 短信签名
	public function createSign()
	{
		//
		$post = input('post.');
		
		//数据
		$data = [
			'sign' => $post['sign'],
			'mch_id' => $this->user['mch_id'],
		];
		
		//数据验证
		$result = logics\Sms::createSignV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//
		$res = logic\SmsSign::create($data);
		if( !$res )
		{
			return json(self::returnError('操作失败',$data));
		}
		
		return json(self::returnSuccess(['id'=>$res],'操作成功'));
	}
	
	//获取签名信息
	public function getSign()
	{
		//APi ID 
		$id = (int)input('id');
		
		//获取
		//$res = logic\Api::getApiById($id);
		if( !($id && $res = logic\SmsSign::getMchSignById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,签名不存在'));
		}

		return json(self::returnSuccess(['data'=>$res],'获取成功'));
	}
	
	//设置签名信息
	public function setSign()
	{
		//
		$id = (int)input('id');
		//
		$post = input('post.');
		
		//1.是否存在
		//$result = logic\Api::getApiById($id);
		if( !($id && $result = logic\SmsSign::getMchSignById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,签名不存在'));
		}
		
		//数据
		$data = [
			'sign' => $post['sign'],
			'mch_id' => $this->user['mch_id'],
		];
		
		//2.数据验证
		$result = logics\Sms::setSignV( $id, $data);
		if( $result !== true ){
			return json(self::returnError($result));
		}
		
		//设置
		$res = logic\SmsSign::setById($id, $data);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess([],'操作成功'));
		
	}
	
	//开关签名状态
	public function setSignStatus()
	{
		//
		$id = (int)input('id');
		
		//1.是否存在
		//$result = logic\Api::getApiById($id);
		if( !($id && $result = logic\SmsSign::getMchSignById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,签名不存在'));
		}
		
		$data = [
			'status' => $result['status'] ? 0: 1,
		];
		
		$status = $result['status'] ? '关闭' : '开启';
		
		//设置
		$res = logic\SmsSign::setById($id, $data);
		if( !$res )
		{
			return json(self::returnError( $status . '失败' ));
		}
		
		return json(self::returnSuccess([], $status . '成功' ));
	}

	//删除签名
	public function deleteSign()
	{
		//
		$id = (int)input('id');
		
		//1.是否存在
		if( !($id && $result = logic\SmsSign::getMchSignById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,签名不存在'));
		}

		//设置
		$res = logic\SmsSign::cancelById($id);
		if( !$res )
		{
			return json(self::returnError('操作失败'));
		}
		
		return json(self::returnSuccess([],'操作成功'));
	}
	
	//设置模板默认
	public function setTempDefault()
	{
		//
		$id = (int)input('id');
		
		//1.是否存在
		//$result = logic\Api::getApiById($id);
		if( !($id && $result = logic\SmsTemp::getMchTempById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,模板不存在'));
		}
		
		$status = $result['default'] ? '取消' : '设置';
		
		//设置
		$res = logics\Sms::setTempDefault( $result );
		if( !$res )
		{
			return json(self::returnError( $status . '失败' ));
		}
		
		return json(self::returnSuccess([], $status . '成功' ));
	}
	
	//设置签名默认
	public function setSignDefault()
	{
		//
		$id = (int)input('id');
		
		//1.是否存在
		//$result = logic\Api::getApiById($id);
		if( !($id && $result = logic\SmsSign::getMchSignById($id, $this->user['mch_id']) ) ) 
		{
			return json(self::returnError('操作异常,签名不存在'));
		}
		
		$status = $result['default'] ? '取消' : '设置';
		
		//设置
		$res = logics\Sms::setSignDefault( $result );
		if( !$res )
		{
			return json(self::returnError( $status . '失败' ));
		}
		
		return json(self::returnSuccess([], $status . '成功' ));
	}
	

}