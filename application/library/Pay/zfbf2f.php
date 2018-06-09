<?php
namespace Pay;

use \Payment\Client\Charge;
use \Payment\Notify\PayNotifyInterface;
use \Payment\Common\PayException;
use \Payment\Config;

class zfbf2f implements PayNotifyInterface
{
	public function pay($payconfig,$params)
	{
		$config = [
			'use_sandbox' => false,
			'app_id' => $payconfig['app_id'],
			'sign_type' => $payconfig['sign_type'],
			'ali_public_key' => $payconfig['ali_public_key'],
			'rsa_private_key' => $payconfig['rsa_private_key'],
			'notify_url' => SITE_URL . $payconfig['notify_url'] . '?paymethod='.$payconfig['alias'],
			'return_url' =>SITE_URL. $payconfig['notify_url'].'?paymethod='.$payconfig['alias'].'&orderid='.$params['orderid'],
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
	
	
	public function notifyProcess(array $data)
	{
		$m_order = \Helper::import('order');
		$m_payment = \Helper::import('payment');
		$m_products_card = \Helper::import('products_card');
		$m_email_queue = \Helper::import('email_queue');
		$m_products = \Helper::import('products');
		
		file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($data).PHP_EOL, FILE_APPEND);
		
		
		
	}
}