<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-28
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace Addons\Uesms;

class Sms
{
	
	static protected $INIT;
	
	static public function getInit()
	{
		if( !static::$INIT )
		{
			static::$INIT = new static();
		}
		
		return static::$INIT;
	}
	
	//发送短信
	static public function send($phone, $content)
	{
		$mobiles = [
			$phone,
		];
		
		$init = static::getInit();
		
		$result = $init->request($mobiles, $content);
		
		if( (int)$result['errorcode'] !== 1 )
		{
			return $init->getMsg($result['errorcode']);
		}
		
		return $result;
	}
	
	/** ========== **/
	
	//服务器地址
	private $serverAddress = "http://sms.ue35.net/sms";
	
	//用户名
	private $username = 'gznf';
	
	//密码
	private $password = '100900';
	
	//请求状态列表
	private $messages = [
		'1'     =>    '操作成功',
		'-1'    =>    '用户名或密码为空',
		'-2'    =>    '用户名或者密码错误',
		'-3'    =>    '用户名被锁',
		'-7'    =>    '发送内容为空',
		'-8'    =>    '余额不足',
		'-9'    =>    '没有正确的号码',
		'-10'   =>    '提交的logid记录不存在',
		'-11'   =>    '内容里有不允许的关键字',
		'-12'   =>    '本次的手机号码列表超过1000个',
		'-999'  =>    '读取余额时出错',
		'-100'  =>    '系统错误',
	];

	//初始化
	public function __construct()
	{
		
		
	}
	
	//拼接请求信息
	private function getData(array $data)
	{
		//用户数据
		$user = [
			'username' => $this->username,
			'userpwd'  => $this->password,
		];
		
		$Data = array_merge($data, $user);
		
		return $Data;
	}
	
	//获取错误信息
	public function getMsg($key)
	{
		$key = (string)$key;
		$msg = $this->messages[$key] ?: $this->messages['-100'];
		
		return $msg;
	}
	
	/**
	发送短信请求
		$mobiles 手机号码列表，最大1000个，号码间以英文分号 ; 分隔 (必填)
		$content 要提交的短信内容，中文内容要使用UTF-8字符集进行URL编码，避免有特殊符号造成提交失败，例如c# 用HttpUtility.UrlEncode("发送内容",Encoding.UTF8) ，java用URLEncoder.encode("发送内容", " UTF-8") (必填)
		$logid 短信id，同一批内容相同的短信第一次发送时可以为空，第二次可以用第一次用本函数后返回的值 (非必填)
		$mobilecount 发送时间，用于定时短信。这里使用的是Unix时间戳。(非必填)
	**/
	public function request(array $mobiles, $content, $logid = '', $mobilecount = false)
	{
		//请求路径
		$URL = "/interface/sendmess.htm";
		
		//号码列表 与 短信内容 不可为空
		if( empty($mobiles) || empty($content) ) return false;
		
		//数据
		$data = [];
		//手机号码
		$data['mobiles'] = join(';', $mobiles);
		//短信内容
		$data['content'] = $content;
		//短信ID
		$data['logid'] = $login;
		//本批提交号码的总数；(非必填)
		$data['mobilecount'] = count($mobiles);
		//发送时间，用于定时短信。这里使用的是Unix时间戳。(非必填)
		$data['mobilecount'] = $mobilecount ?: NOWTIME;
		
		//提交数据
		$post = $this->getData($data);
		
		//提交数据
		$result = curl_request($this->serverAddress . $URL, 'post', $post);
		
		if( $result === false ) return false;
		
		$DATA = $this->FromXml($result);
		
		return $DATA;
	}
	
	//获取XML数据
	protected function FromXml( $xml )
	{
		if( empty($xml) ) return false;
		//
		$xmlData = json_decode( json_encode( simplexml_load_string( $xml, 'SimpleXMLElement', LIBXML_NOCDATA) ), true);
		
		return $xmlData;
	}

}