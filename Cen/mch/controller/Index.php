<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-07 16:05:53
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------
namespace app\mch\controller;



class Index
{
	//构造函数
	public function __construct()
	{
	
	}
	
	//商户注册
	public function register()
	{
		$post = input('post.');
		
		//数据
		$data = [
			'name' => $post['name'],
			'nick' => $post['nick'],
			'phone' => $post['phone'],
			'password' => $post['password'],
		];
		
		//验证
		//$result = 
		
	}

}