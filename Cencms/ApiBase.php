<?php
namespace Cencms;

class ApiBase
{

	//构造函数
	public function __construct()
	{
		
	}
	
	//返回数据
	protected function returnData($status,$msg = '')
	{
		//基础参数
		$data = [
			'status' => 0,
			'msg' => ''
		];
		//状态
		$data['status'] = $status ? 1 : 0 ;
		//信息
		$data['msg'] = is_string($msg) ? $msg : '';
		
		return $data;
	}
	
	//成功返回
	protected function returnSuccess($arr = [],$msg = 'Success')
	{
		//基础数据
		$data = self::returnData(1,$msg);
		//返回数据
		$returnData = array_merge($arr,$data); //附加数据放在第一个参数,防止基础数据被替换
		
		return $returnData;
	}
	
	//失败返回
	protected function returnError($msg = 'Flie',$arr = [])
	{
		//基础数据
		$data = self::returnData(0,$msg);
		//返回数据
		$returnData = array_merge($arr,$data);
		
		return $returnData;
	}

}