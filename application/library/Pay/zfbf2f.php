<?php
/**
 * File: zfbf2f.php
 * Functionality: 支付宝面对面支付
 * Author: 资料空白
 * Date: 2018-6-8
 */
namespace Pay;

use \Payment\Client\Charge;
use \Payment\Notify\PayNotifyInterface;
use \Payment\Common\PayException;
use \Payment\Config;
use \Pay\notify;

class zfbf2f implements PayNotifyInterface
{
	//处理请求
	public function pay($payconfig,$params)
	{
		$config = [
			'use_sandbox' => false,
			'app_id' => $payconfig['app_id'],
			'sign_type' => $payconfig['sign_type'],
			'ali_public_key' => $payconfig['ali_public_key'],
			'rsa_private_key' => $payconfig['rsa_private_key'],
			'notify_url' => $params['web_url'] . $payconfig['notify_url'],
			'return_url' =>$params['web_url']. $payconfig['notify_url'].'?paymethod=zfbf2f&orderid='.$params['orderid'],
			'return_raw' => true
		];

		$data = [
			'order_no' => $params['orderid'],
			'amount' => $params['money'],
			'subject' => $params['productname'],
			'body' => 'zfbf2f', 
		];
		try {
			$qr = Charge::run(Config::ALI_CHANNEL_QR, $config, $data);
			$result = array('paymethod'=>'zfbf2f','qr'=>$qr);
			return array('code'=>1,'msg'=>'success','data'=>$result);
		} catch (PayException $e) {
			return array('code'=>1000,'msg'=>$e->errorMessage(),'data'=>'');
		}
	}
	
	//处理返回
	public function notifyProcess(array $params)
	{
		if($params['body']=='zfbf2f'){
			$config = array('paymethod'=>$params['body'],'tradeid'=>$params['trade_no'],'paymoney'=>$params['total_amount'],'orderid'=>$params['out_trade_no'] );
			$notify = new \Pay\notify();
			$data = $notify->run($config);
		}else{
			$data =array('code'=>1002,'msg'=>'支付方式不对');
		}
		return $data;
	}
	
}