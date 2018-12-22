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

class Sms extends Base//\Cencms\ApiBase
{
	//构造函数
	public function __construct()
	{
		//父级构造函数
		parent::__construct();
		
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
		
		$result = logics\Sms::sendV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		//短信发送
		$send = logic\Sms::send($data['code'],$data['phone']);
		
		if( !$send ) 
		{
			return json(self::returnError('发送失败'));
		}
		
		return json(self::returnSuccess([],'发送成功'));
	
	}
	
	//

}