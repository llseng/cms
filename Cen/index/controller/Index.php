<?php
namespace app\index\controller;

use app\common\logic as logic;

class Index extends \think\Controller
{
	
    public function index()
    {
		
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
	
	
	private function up(\CURLFile $file)
	{
		
		$url = "http://www.resource.cn/api/Images/index";
		
		//curl上传数据
		$post_data = array(
			"foo" => "bar",
			//要上传的本地文件地址
			"upload" => $file,
		);
		
		$ch = curl_init();
		
		curl_setopt($ch , CURLOPT_URL , $url);
		
		curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch , CURLOPT_POST, 1);
		
		curl_setopt($ch , CURLOPT_POSTFIELDS, $post_data);
		
		$output = curl_exec($ch);
		
		curl_close($ch);
		
		return $output;
		
	}
	
	//测试curl上传
	public function test()
	{
		//文件对象
		$file = $_FILES['file'];
		
		//保存路径
		$path = './public/test/';
		
		if($file['error'] !== 0) return '上传失败';
		
		echo '<pre>';
		
		var_dump($_FILES['file']);
		
		!file_exists($path) && mkdir($path,777,true);
		
		if( move_uploaded_file($file['tmp_name'] , $path . $file['name']) )
		{
			//return '上传成功';
			echo '上传成功';
			
			//上传到资源服务器
			
			/***生成文件对象***/
			$file_obj = new \CURLFile($path . $file['name'] , $file['type'],$file['name']);
			
			return $this->up( $file_obj );
			
		}else{
			return '上传失败';
		}
		
	}

}
