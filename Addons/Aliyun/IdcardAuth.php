<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace Addons\Aliyun;

class IdcardAuth
{
	private $appcode = '';

	//构造函数
	public function __construct(){
	
		//Appcode
		$this->appcode = '7256d0fd52274302b33dea3cf591f2ed';
	}
	
	//请求第三方
	/**
	* idCard 身份证号
	* realName 真实姓名
	* return 返回官方参数
	*/
	public function request($idCard,$realName)
	{
		//请求地址
		$host = "https://idcert.market.alicloudapi.com";
		//请求目录
		$path = "/idcard";
		//请求类型
		$method = "GET";
		//商户appcode
		$appcode = $this->appcode;
		//请求头
		$headers = array();
		//请求头拼接 商户appcode
		array_push($headers, "Authorization:APPCODE " . $appcode);
		//请求参数
		$querys = "idCard={$idCard}&name={$realName}";
		
		$bodys = "";
		
		$url = $host . $path . "?" . $querys;

		//$result = curl_request($url,$method,[],$headers);
		$result = '{"status":"205","msg":"身份证格式不正确！","idCard":"123456789123456789","name":"林胜","sex":"","area":"","province":"","city":"","prefecture":"","birthday":"","addrCode":"","lastCode":""}';
		//返回响应数据
		return $result;

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
	
	//认证
	static public function auth($idCard,$realName)
	{
		//获取单例
		$init = self::init();
		
		//获取响应数据
		$result = $init->request($idCard,$realName);
		if( !$result ) 
		{
			return '请求第三方失败';
		}
		
		//json解码
		$data = json_decode($result,1);
		if( !$data ) 
		{
			return 'json解码失败:' . var_export($result,1);
		}
		
		//成功 失败 以外的情况
		//认证异常情况
		if( (int)$data['status'] == 202 || (int)$data['status'] == 203 )
		{
			return '接口返回异常:' . $data['msg'];
		}
		
		//入库数据
		$insert = [];
		
		//身份证号
		$insert['idcard'] = $idCard;
		//真实姓名
		$insert['real_name'] = $realName;
		//认证状态
		(int)$data['status'] == 1 && $insert['status'] = 1;
			
		//接口返回信息
		$insert['msg'] = $data['msg'];
		
		//认证成功 才有 附加数据
		if( $insert['status'] ) 
		{
			//性别
			$insert['sex'] = $data['sex'] = '男' ? 1 : 2;
			
			//身份证所在地
			$insert['addr'] = $data['area'];
			
			//地区编码
			$insert['addr_code'] = $data['addrCode'];
			
			//出生年月
			$insert['birth'] = $data['birthday'];
			
		}
		
		return $insert;
		
	}

	
}