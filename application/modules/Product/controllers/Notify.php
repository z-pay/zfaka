<?php
/*
 * 功能：产品中心-支付回调(异步处理)
 * author:资料空白
 * time:20180509
 */
use \Payment\Common\PayException;
use \Payment\Client\Notify;
use \Pay\zfbf2f;
class NotifyController extends PcBasicController
{
	private $m_order;
	private $m_payment;
	private $m_products_card;
	private $m_email_queue;
	private $m_products;
    public function init()
    {
        parent::init();
		$this->m_order = $this->load('order');
		$this->m_payment = $this->load('payment');
		$this->m_products_card = $this->load('products_card');
		$this->m_email_queue = $this->load('email_queue');
		$this->m_products = $this->load('products');
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
					echo $e->errorMessage();
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


}