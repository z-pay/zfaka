<?php
/**
 * File: zfbf2f.php
 * Functionality: 支付宝面对面支付
 * Author: 资料空白
 * Date: 2018-6-8
 */
namespace Pay\zfbf2f;

use \Payment\Client\Charge;
use \Payment\Common\PayException;
use \Payment\Client\Notify;
use \Payment\Config;

use \Pay\zfbf2f\callback;

class zfbf2f
{
	private $paymethod ="zfbf2f";
	//处理请求
	public function pay($payconfig,$params)
	{
		$config = [
			'use_sandbox' => false,
			'app_id' => $payconfig['app_id'],
			'sign_type' => $payconfig['sign_type'],
			'ali_public_key' => $payconfig['ali_public_key'],
			'rsa_private_key' => $payconfig['rsa_private_key'],
			'return_url' => $params['web_url']. '/product/query/?paymethod='.$this->paymethod.'&orderid='.$params['orderid'],
			'notify_url' => $params['web_url'] . '/product/notify/?paymethod='.$this->paymethod,
			'return_raw' => true
		];

		$data = [
			'order_no' => $params['orderid'],
			'amount' => $params['money'],
			'subject' => $params['productname'],
			'body' => $this->paymethod, 
		];
		try {
			$qr = Charge::run(Config::ALI_CHANNEL_QR, $config, $data);
			$result = array('paymethod'=>$this->paymethod,'qr'=>"/product/order/showqr/?url=".$qr,'payname'=>$payconfig['name']);
			return array('code'=>1,'msg'=>'success','data'=>$result);
		} catch (PayException $e) {
			return array('code'=>1000,'msg'=>$e->errorMessage(),'data'=>'');
		}
	}
	
	public function notify(array $payconfig,array $params)
	{
		try {
			unset($_POST['paymethod']);
			$callback = new \Pay\zfbf2f\callback();
			$ret = Notify::run("ali_charge", $payconfig,$callback);// 处理回调，内部进行了签名检查
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($ret).PHP_EOL, FILE_APPEND);
			var_dump($ret);
			exit();
		} catch (\Exception $e) {
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.$e->errorMessage().PHP_EOL, FILE_APPEND);
			exit;
		}
	}
	
}