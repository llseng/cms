<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//所有错误信息
error_reporting(E_ALL &  ~E_NOTICE);

//请求时间搓
!defined("NOWTIME") && define("NOWTIME",time());

//curl请求
function curl_request($url,$method = "get",array $data = [],array $header = [],$cookie = '')
{
    //是否是正确链接
    if(!preg_match("/^(http|https):\/\/.+/i",$url)) return false;
    
    //开启一个 curl 句柄
    $conn = curl_init();

    //curl 选项
    $setopt = [];

    //设置 是否获取头部输出信息
    $setopt[CURLOPT_HEADER] = false;

    //获取请求头
    $setopt[CURLINFO_HEADER_OUT] = true;

    //设置 最大执行时间
    $setopt[CURLOPT_TIMEOUT] = 10; //秒

    //设置 curl_exec()获取的信息以字符串返回
    $setopt[CURLOPT_RETURNTRANSFER] = true;

    //输出路径
    //$setopt[CURLOPT_FILE] =  $fp;//默认STDOUT

    //请求类型
    if(strtoupper($method) == "GET")
    {
        //拼装请求路径
        $data && $url .= "?" . http_build_query($data);
    }else{
        //设置为POST请求
        $setopt[CURLOPT_POST] = true;
        //请求数据
        $setopt[CURLOPT_POSTFIELDS] = $data;
    }

    //设置请求路径
    $setopt[CURLOPT_URL] = $url;
	
    //是否是HTTPS请求
    if(preg_match("/https:\/\//i",$url))
    {
        //FALSE 禁止 cURL 验证对等证书 
        $setopt[CURLOPT_SSL_VERIFYPEER] = false;
        //1 是检查服务器SSL证书中是否存在一个公用名
        $setopt[CURLOPT_SSL_VERIFYHOST] = 2;
    }
	
	//请求 header
	$setopt[CURLOPT_HTTPHEADER] = $header;

    //请求 cookie
    if($cookie && is_array($cookie))
    {
        $cookieArr = $cookie;
        $cookie = "";
        foreach($cookieArr as $key => $val)
        {
            $cookie .= $key . "=" . $val . ";";
        }
    }
    
    $setopt[CURLOPT_COOKIE] = $cookie;

    //设置 curl 选项
    curl_setopt_array($conn,$setopt);

    //执行curl句柄
    $result = curl_exec($conn);

    //调试
    //if($result === false) var_dump(curl_error($conn));
    //var_dump(curl_getinfo($conn));
    //释放 curl 句柄
    curl_close($conn);

    //fclose($fp);
	
    return $result;
}

//打印JSON数据
function printJSON(array $data)
{
    exit(json_encode($data));
}

//随机字符串
function rand_string($length = 4,$num = 10)
{
    
	if($length < 4) $length = 4;
	if($num > 62 || $num < 10) $num = 10;
	
    $stringArr = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	
	$str = "";
	
	while($length--)
	{
        $rand = rand(0,$num-1);
        
        $str .= $stringArr[$rand];
        
	}
	
	return $str;
}

//密码加密
function cen_md5($string)
{
    //字符前缀 (不可乱改不然 现有账户登录不了)
    $prefix = "cenCMS";

    return md5( $prefix . $string );

}

//去除所有空格
function trim_all($str)
{
    is_string($str) || $str = (string)$str;
    if( !$str ) return '';

    //查找的值
    $search = [' ','  ',"\r","\n","\t","&nbsp;"];

    //替换的值
    $replace = "";

    return str_replace($search, $replace, $str);
}

//去除数组下说有字符的空格
function array_trim_all(array $arr, $recursive = false)
{
    if( !$arr ) return [];

    foreach( $arr as $key => &$val )
    {
        if( is_array($val) && $recursive ) 
        {
            $val = array_trim_all($val,true);
            continue;
        }

        if( !is_string($val) ) continue;

        $val = trim_all($val);
    }
    
    return $arr;

}

//是否是手机号
function is_phone($phone)
{
	$result = preg_match("/^1[3|4|5|7|8]\d{9}$/",$phone);
	
	return $result;
}

//是否是IP
function is_ip($ip)
{
	$result = filter_var($ip,FILTER_VALIDATE_IP) === false;
	
	return $result;
}