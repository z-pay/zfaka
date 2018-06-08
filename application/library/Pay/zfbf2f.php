<?php
namespace Pay;

use \Payment\Client\Charge;
use \Payment\Common\PayException;
use \Payment\Config;

class zfbf2f{
	function pay($payconfig,$params){
		$config = [
			'use_sandbox' => false,
			'app_id' => $payconfig['email'],    //应用appid
			'sign_type' => 'RSA2',
			'ali_public_key' => $payconfig['appid'],
			'rsa_private_key' => $payconfig['appsecret'],
			'notify_url' => SITE_URL . '/pay/zfbf2f/notify.php', //异步通知地址
			'return_url	' =>  SITE_URL. '/chaka?oid='.$params['orderid'],
			'return_raw' => true
		];

		$data = [
			'order_no' => $params['orderid'],     //商户订单号，需要保证唯一
			'amount' => $params['money'],           //订单金额，单位 元
			'subject' => $params['productname'],      //订单标题
			'body' => 'zfbf2f',      //订单标题
		];
		try {
			$str = Charge::run(Config::ALI_CHANNEL_QR, $config, $data);
			return array('code'=>1,'msg'=>'success','data'=>$str);
		} catch (PayException $e) {
			return array('code'=>1000,'msg'=>$e->errorMessage(),'data'=>'');
		}
	}
}