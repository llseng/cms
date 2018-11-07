<?php
namespace app\api\logic;

use app\comment\logic as logics;
use app\api\validate as validates;
use app\api\comtroller as controllels;


class Card
{
	//构造函数
	public function __construct()
	{
		
	
	}
	
	//认证请求数据验证
	static public function authV($data)
	{
		$validate = new validates\Card();

		$result = $validate->check($data);
		
		if( !$result )
		{
			return $validate->getError();
		}
		
		return true;
	}
	

}