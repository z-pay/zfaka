<?php
/*
 * 功能：产品中心-支付回调(异步处理)
 * Author:资料空白
 * Date:20180509
 */
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
			$paymethod = isset($_GET['paymethod'])?$_GET['paymethod']:(isset($_POST['paymethod'])?$_POST['paymethod']:'zfbf2f');
			$payments = $this->m_payment->getConfig();
			if(isset($payments[$paymethod]) AND !empty($payments[$paymethod])){
				try {
					$payconfig = $payments[$paymethod];
					$payclass = "\\Pay\\".$paymethod."\\".$paymethod;
					$PAY = new $payclass();
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