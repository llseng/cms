<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-12-27
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use \Request;

class MchNotice
{
	static $INIT;
	
	static public function getInit()
	{
		if( !self::$INIT )
		{
			self::$INIT = new self();
		}
		
		return self::$INIT;
	}
	
	//推送
	static public function push($type)
	{
		$init = self::getInit();
		
		$init->notice($type);
	}
	
	//记录
	static public function logNotice($msg,$receive)
	{
		Logger::log(
		[
			'msg' => $msg,
			'receive' => $receive,
		],__DIR__ . '/' . 'MchNotice/' . date("Y-m/d") . '.log');
		
	}

	//构造函数
	public function __construct(array $mchData = [])
	{
		//
		$this->mch = $mchData ?: Mch::getLogin();
	
	}
	
	//商户信息
	private $mch;
	private $type;
	//设置类型
	public function setType($type)
	{
		$type = (string)$type;
		$this->type = strtoupper($type);
	}
	
	private $mode;
	//设置方式
	public function setMode($mode)
	{
		$mode = (string)$mode;
		$this->mode = strtoupper($mode);
	}
	
	
	//通知类型
	private $typeList = [
		'LOGIN' => [
			'title' => '登录通知',
			'msg' => '您的账号于{date}在{ip}登录!',
		],
		
		'CHANGE_PASS' => [
			'title' => '修改密码通知',
			'msg' => '您的账号于{date}修改密码成功!',
		],
	];
	
	//获取通知类型
	public function getType()
	{
		$type = $this->typeList[$this->type] ?: [];
		
		return $type['title'];
	}
	
	public function getTypeMsg()
	{
		$type = $this->typeList[$this->type] ?: [];
		
		if( !$type ) return '';
		
		//不区分大小写替换
		$msg = str_ireplace(
			[
				'{date}',
				'{ip}',
				'{mchName}'
			],
			[
				date('Y-m-d H:i:s'),
				Request::ip(),
				$this->mch['name']
			],
			$type['msg']
		);
		
		$msg = preg_replace('/\{(.*)\}/','',$msg);
		
		return $msg;
	}
	
	//通知方式
	private $modeList = [
		'SMS' => [
			'title' => '短信通知',
			'func' => __CLASS__ . '::logNotice',
		],
		'EMAIL' => [
			'title' => '邮箱通知',
			'func' => __CLASS__ . '::logNotice',
		],
	];
	
	//获取通知方式
	public function getMode()
	{
		$mode = $this->modeList[$this->mode] ?: [];
		if( !$mode ) return '';
		
		return $mode['func'];
	}
	
	//获取接收人
	public function getReceive()
	{
		switch( $this->mode )
		{
			case 'SMS':
				return $this->mch['phone'];
			break;
			case 'EMAIL':
				return $this->mch['email'];
			break;
			default:
				return false;
			break;
		}
	}
	
	//通知
	public function notice($type, $mode = 'SMS')
	{
		$this->setType($type);
		
		$this->setMode($mode);
		
		$this->send();
	}
	
	//
	public function send()
	{
		//发送信息
		$msg = $this->getTypeMsg();
		//发送方式
		$mode = $this->getMode();
		//接收方
		$receive = $this->getReceive();

		if( !$msg || !$mode || !$receive ) return false;
		
		call_user_func($mode,$msg,$receive);
	}

}