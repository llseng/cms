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
	
	//构造函数
	public function __construct()
	{
		//父级构造函数
		parent::__construct();
		
		$this->createApplyPhone();
	}

	//发送营销短信
	public function sendMarket()
	{
		
	}

	//发送验证短信(单条)
	public function sendCode()
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

		//有效期
		$time = $post['time'] ?: 10;
		
		//短信发送
		$send = logic\Sms::sendCode($data['code'],$time,$data['phone'],$post['mch_id']);
		
		if( !$send ) 
		{
			return json(self::returnError('发送失败'));
		}
		
		return json(self::returnSuccess([],'发送成功'));
	
	}
	
	//创建手机应用关联数据
	private function createApplyPhone()
	{
		if( $this->mchApplyData )
		{
		
			$applyPhone = logic\ApplyPhone::getApplyPhone((int)$_POST['apply_id'], $_POST['phone']);
			if( $applyPhone ) return false;
			
			$phoneData = logic\Phone::getPhoneData($_POST['phone']);
			if( !$phoneData ) return false;
			
			$data = [];
			$data['apply_id'] = $this->mchApplyData['id'];
			$data['apply_name'] = $this->mchApplyData['name'];
			
			$data['phone_id'] = $phoneData['id'];
			$data['phone'] = $phoneData['phone'];
			
			$result = logic\ApplyPhone::create($data);
			
			return $result ?: false;
			
		}
	}

}