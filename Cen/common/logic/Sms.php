<?php
// +----------------------------------------------------------------------
// | LLS_WOODS [ Constantly improve yourself ]
// +----------------------------------------------------------------------
// | Copyright (c) 念菲网络 (http://www.cencms.com)
// +----------------------------------------------------------------------
// | Creation time 2018-11-07
// +----------------------------------------------------------------------
// | Author: lls_woods <1300904522@qq.com>
// +----------------------------------------------------------------------

namespace app\common\logic;

use Addons\Aliyun\SmsHelper;

class Sms
{
	
	//秘钥ID
	static public $akId = "LTAI0maUqu8JtmWg";
	
	//秘钥
	static public $akSecret = "1L5pI9QoqtJpcjzdzOxmXO5fHTSFJk";

	/**
	 * 发送短信
	 * @code  验证码
	 * @phone  手机号
	 * @product  签名
	 * @tempcode  模板ID
	 */
	static public function sendSms($code,$phone,$product = '念菲网络',$tempcode = 'SMS_116820224') {

		$params = array ();

		// *** 需用户填写部分 ***
		// fixme 必填：是否启用https
		$security = false;

		// fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
		$accessKeyId = self::$akId;
		$accessKeySecret = self::$akSecret;

		// fixme 必填: 短信接收号码
		$params["PhoneNumbers"] = $phone;

		// fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
		$params["SignName"] = $product;

		// fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
		$params["TemplateCode"] = $tempcode;

		// fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
		$params['TemplateParam'] = Array (
			"code" => $code,
			"product" => '1'
		);

		// fixme 可选: 设置发送短信流水号
		$params['OutId'] = "12345";

		// fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
		$params['SmsUpExtendCode'] = "1234567";


		// *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
		if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
			$params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
		}

		// 初始化SignatureHelper实例用于设置参数，签名以及发送请求
		$helper = new SmsHelper();

		// 此处可能会抛出异常，注意catch
		$content = $helper->request(
			$accessKeyId,
			$accessKeySecret,
			"dysmsapi.aliyuncs.com",
			array_merge($params, array(
				"RegionId" => "cn-guangzhou",
				"Action" => "SendSms",
				"Version" => "2018-11-07",
			)),
			$security
		);

		return $content;
	}


	//构造函数
	public function __construct()
	{
		
	}
	
	
}