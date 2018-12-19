<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-19
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\logic as logic;
use app\admin\logic as logics;

class Index extends \Cencms\ApiBase
{

	//构造函数
	public function __construct()
	{
		//父级构造函数
		parent::__construct();
	
	}
	
	
	
	public function index()
	{
		
		return $this->fetch();
		
	}
	
	//登录
	public function login()
	{
		//验证码
		$code = input('code');

		if( !$this->verifyLoginCaptcha($code) )
		{
			//return json(self::returnError('验证码错误'));
		}
		//
		$post = input('post.');
		
		//数据
		$data = [
			'username' => $post['username'],
			'password' => $post['password'],
		];
		
		//数据验证
		$result = logics\Admin::loginV($data);
		if( $result !== true )
		{
			return json(self::returnError($result));
		}
		
		$res = logic\Admin::login($data);
		if( !$res )
		{
			return json(self::returnError('账户名或密码错误'));
		}
		
		return json(self::returnSuccess([],'登录成功'));
	}
	
	//登录验证码
	public function loginCaptcha()
	{
		//
		$captcha = new \think\Captcha();
		
		return $captcha->entry('login');
	}
	
	//验证登录验证码
	private function verifyLoginCaptcha( $code )
	{
		//
		$captcha = new \think\Captcha();
		
		return $captcha->check($code, 'login');
	}
	
	//退出登录
	public function login_out()
	{
		logic\Admin::clearSession();
		
		return json(self::returnSuccess([],'操作成功'));
	}
	
}