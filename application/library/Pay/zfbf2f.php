<?php
namespace Pay;

use \Payment\Client\Charge;
use \Payment\Common\PayException;
use \Payment\Config;

class zfbf2f{
	function pay($payconfig,$params){
		$config = [
			'use_sandbox' => false,
			'app_id' => $payconfig['email'],
			'sign_type' => 'RSA2',
			'ali_public_key' => $payconfig['appid'],
			'rsa_private_key' => $payconfig['appsecret'],
			'notify_url' => SITE_URL . '/product/notify/?paymethod=zfbf2f',
			'return_url	' =>  SITE_URL. '/product/query/?paymethod=zfbf2f&orderid='.$params['orderid'],
			'return_raw' => true
		];

		$data = [
			'order_no' => $params['orderid'],
			'amount' => $params['money'],
			'subject' => $params['productname'],
			'body' => 'zfbf2f', 
		];
		try {
			$str = Charge::run(Config::ALI_CHANNEL_QR, $config, $data);
			return array('code'=>1,'msg'=>'success','data'=>$str);
		} catch (PayException $e) {
			return array('code'=>1000,'msg'=>$e->errorMessage(),'data'=>'');
		}
	}
}