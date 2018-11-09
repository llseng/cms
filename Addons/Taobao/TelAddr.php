<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-09 09:22:54
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace Addons\Taobao;


class telAddr
{
	//接口地址
	private $url;

	//构造函数
	public function __construct()
	{
		$this->url = "https://tcc.taobao.com/cc/json/mobile_tel_segment.htm";
	}
	
	//请求第三方
	public function request($phone)
	{
		//请求地址
		$url = $this->url;
		
		//请求数据
		$data = [
			'tel' => $phone
		];
		
		//请求类型
		$method = "GET";
		
		//请求
		$result = curl_request($url,$method,$data);
		//返回字符集 转换为 utf-8
		$result = iconv("GBK","UTF-8",$result);
		
		$matches = [];
		//不存在json字符
		if( !preg_match("/\{[^\}]+?\}/",$result,$matches) )
		{
			return false;
		}
		//格式转换为json_decode可解析字符
		$dataStr = preg_replace("/([a-zA-z]+)|\'([^\']+)\'/",'"$1$2"',$matches[0]);
		
		$data = json_decode($dataStr,1);
		
		return $data ?: false;
	}
	
	//单例
	static public $init;
	
	//获取单例
	static public function init()
	{
		if( !self::$init ) 
		{
			self::$init = new self();
		}
		
		return self::$init;
	}

	//获取手机归属地
	static public function get($phone)
	{
		//单例
		$init = self::init();
		
		$result = $init->request($phone);
		if( !$result )
		{
			return '第三方请求失败';
		}

		//入库数据
		$insert = [];
		
		//运营商
		$insert['company'] = $result['catName'];
		
		//省份
		$insert['province'] = $result['province'];
		
		return $insert;
		
	}
	
}