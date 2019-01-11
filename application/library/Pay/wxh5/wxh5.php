<?php
/**
 * File: wxh5.php
 * Functionality: 微信h5支付
 * Author: 资料空白
 * Date: 2018-09-05
 */
namespace Pay\wxh5;

use \Payment\Client\Charge;
use \Payment\Common\PayException;
use \Payment\Client\Notify;
use \Payment\Config;

use \Pay\wxh5\callback;

class wxh5
{
	private $paymethod ="wxh5";
	//处理请求
	public function pay($payconfig,$params)
	{
		$config = [
			'use_sandbox' => false,
			'app_id' => $payconfig['app_id'],
			'mch_id' => $payconfig['configure3'],
			'md5_key' => $payconfig['app_secret'],
			'sign_type' => $payconfig['sign_type'],
			'app_cert_pem' => LIB_PATH.'Pay/'.$this->paymethod.'/pem/weixin_app_cert.pem',
			'app_key_pem' => LIB_PATH.'Pay/'.$this->paymethod.'/pem/weixin_app_key.pem',
			'fee_type'  => 'CNY',
			'redirect_url' => $params['weburl']. "/query/auto/{$params['orderid']}.html",
			'notify_url' => $params['weburl'] . '/product/notify/?paymethod='.$this->paymethod,
			'return_raw' => false
		];

		$data = [
			'body'    => $this->paymethod, 
			'subject'    => $params['productname'],
			'order_no'    => $params['orderid'],
			'timeout_express' => time() + 600,// 表示必须 600s 内付款
			'amount'    => $params['money'],
			'return_param' => '',
			'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
			'scene_info' => [
				'type' => 'Wap',// IOS  Android  Wap  腾讯建议 IOS  ANDROID 采用app支付
				'wap_url' => $params['weburl'],//自己的 wap 地址
				'wap_name' => 'WAP',
			],
		];
		try {
			$qr = Charge::run(Config::WX_CHANNEL_WAP, $config, $data);
			if($qr){
				$result_params = array('type'=>0,'subjump'=>0,'paymethod'=>$this->paymethod,'qr'=>$params['qrserver'].$qr,'payname'=>$payconfig['payname'],'overtime'=>$payconfig['overtime'],'money'=>$params['money']);
				return array('code'=>1,'msg'=>'success','data'=>$result_params);
			}else{
				return array('code'=>1002,'msg'=>'支付接口请求失败','data'=>'');
			}
		} catch (PayException $e) {
			return array('code'=>1001,'msg'=>$e->errorMessage(),'data'=>'');
		} catch (\Exception $e) {
			return array('code'=>1000,'msg'=>$e->getMessage(),'data'=>'');
		}
	}
	
	public function notify(array $payconfig)
	{
		try {
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($_POST).PHP_EOL, FILE_APPEND);
			unset($_POST['paymethod']);
			$callback = new \Pay\wxh5\callback();
			return $ret = Notify::run("wx_charge", $payconfig,$callback);// 处理回调，内部进行了签名检查	
		} catch (\Exception $e) {
			return 'error|Exception:'.$e->getMessage();
		}
	}
	
}