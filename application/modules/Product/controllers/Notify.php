<?php
/*
 * 功能：产品中心-支付回调(异步处理)
 * Author:资料空白
 * Date:20180509
 */
use \Payment\Common\PayException;
use \Payment\Client\Notify;
use \Pay\zfbf2f;
use \Pay\codepayalipay;
class NotifyController extends PcBasicController
{
	private $m_payment;
    public function init()
    {
        parent::init();
		$this->m_payment = $this->load('payment');
    }

	
    public function indexAction()
    {
		if(!empty($_POST)){
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($_POST).PHP_EOL, FILE_APPEND);
			$paymethod = $_POST['body'];
			$payments = $this->m_payment->getConfig();
			unset($_POST['paymethod']);
			if($paymethod == 'zfbf2f'){
				$callback = new \Pay\zfbf2f();
				$payconfig = $payments[$paymethod];
				try {
					$ret = Notify::run("ali_charge", $payconfig,$callback);// 处理回调，内部进行了签名检查
					file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($ret).PHP_EOL, FILE_APPEND);
					var_dump($ret);
					exit();
				} catch (PayException $e) {
					file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.$e->errorMessage().PHP_EOL, FILE_APPEND);
					exit;
				}
			}else{
				echo 'error';exit();
			}
		}else{
			echo 'error';exit();
		}
    }
	
    public function zfbf2fAction()
    {
		if(!empty($_POST)){
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($_POST).PHP_EOL, FILE_APPEND);
			$paymethod = 'zfbf2f';
			$payments = $this->m_payment->getConfig();
			if(isset($payments[$paymethod]) AND !empty($payments[$paymethod])){
				$payconfig = $payments[$paymethod];
				unset($_POST['paymethod']);
				try {
					$callback = new \Pay\zfbf2f();
					$ret = Notify::run("ali_charge", $payconfig,$callback);// 处理回调，内部进行了签名检查
					file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($ret).PHP_EOL, FILE_APPEND);
					var_dump($ret);
					exit();
				} catch (PayException $e) {
					file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.$e->errorMessage().PHP_EOL, FILE_APPEND);
					exit();
				}
			}else{
				echo 'error';exit();
			}
		}else{
			echo 'error';exit();
		}
    }
	
    public function codepayalipayAction()
    {
		if(!empty($_POST)){
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($_POST).PHP_EOL, FILE_APPEND);
			$paymethod = 'codepayalipay';
			$payments = $this->m_payment->getConfig();
			if(isset($payments[$paymethod]) AND !empty($payments[$paymethod])){
				$payconfig = $payments[$paymethod];
				try {
					$callback = new \Pay\codepayalipay();
					$callback->notifyProcess($payconfig,$_POST);
					exit();
				} catch (PayException $e) {
					file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.$e->errorMessage().PHP_EOL, FILE_APPEND);
					exit();
				}
			}else{
				echo 'error';exit();
			}
		}else{
			echo 'error';exit();
		}
    }
}