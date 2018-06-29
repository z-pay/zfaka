<?php
/*
 * 功能：产品中心-支付回调(异步处理)
 * Author:资料空白
 * Date:20180509
 */
use \Payment\Common\PayException;
use \Payment\Client\Notify;
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
		if(!empty($_POST) AND !empty($_GET) AND isset($_GET['paymethod'])){
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($_POST).PHP_EOL, FILE_APPEND);
			$paymethod = $_GET['paymethod'];
			$payments = $this->m_payment->getConfig();
			if(isset($payments[$paymethod]) AND !empty($payments[$paymethod])){
				try {
					$payconfig = $payments[$paymethod];
					$PAY = "\\Pay\\".$paymethod."\\".$paymethod;
					$data = $PAY->notify($payconfig,$_POST);
					if($data['code']=='1'){
						echo 'success';exit();
					}else{
						echo 'error';exit();
					}
				} catch (\Exception $e) {
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