<?php
class IndexController extends PcBasicController {

	public function init(){
        parent::init();
	}

	public function indexAction(){
		if(!$this->login OR empty($this->uinfo)){
			$this->redirect("/product/");
		}else{
			$this->redirect("/member/");
		}
		return FALSE;
	}
	
	public function testAction(){
		$paymethod='zfbf2f';
		$this->m_payment = $this->load('payment');
		$payments = $this->m_payment->getConfig();
		print_r($payments);
			$payconfig = $payments[$paymethod];
			//支付宝公钥，账户中心->密钥管理->开放平台密钥，找到添加了支付功能的应用，根据你的加密类型，查看支付宝公钥
			echo $alipayPublicKey=$payconfig['appid'];
			$aliPay = new \Pay\AlipayService($alipayPublicKey);	
			var_dump($aliPay);	
			exit();		
	}
}