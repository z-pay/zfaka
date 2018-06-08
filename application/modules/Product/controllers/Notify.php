<?php

/*
 * 功能：产品中心-支付回调(异步处理)
 * author:资料空白
 * time:20180509
 */

class NotifyController extends PcBasicController
{
	private $m_order;
	private $m_payment;
    public function init()
    {
        parent::init();
		$this->m_order = $this->load('order');
		$this->m_payment = $this->load('payment');
    }

    public function indexAction()
    {
		$data = array();
		$paymethod = $this->get('paymethod',false);

		$payments = $this->m_payment->getConfig();
		
		if($paymethod=='zfbf2f'){
			$payconfig = $payments[$paymethod];
			//支付宝公钥，账户中心->密钥管理->开放平台密钥，找到添加了支付功能的应用，根据你的加密类型，查看支付宝公钥
			$alipayPublicKey=$payconfig['userid'];
			$aliPay = new \Pay\AlipayService($alipayPublicKey);
			//验证签名
			$result = $aliPay->rsaCheck($_POST,$_POST['sign_type']);
			if($result===true){
				if($_POST['trade_status'] == "TRADE_SUCCESS"){
					$m = array('paymethod'=>$paymethod,'tradeid'=>$_POST['out_trade_no'],'orderid'=>$_POST['trade_no']);
					$this->_doOrder($m);
					//处理你的逻辑，例如获取订单号$_POST['out_trade_no']，订单金额$_POST['total_amount']等
					//程序执行完后必须打印输出“success”（不包含引号）。如果商户反馈给支付宝的字符不是success这7个字符，支付宝服务器会不断重发通知，直到超过24小时22分钟。一般情况下，25小时以内完成8次通知（通知的间隔频率一般是：4m,10m,10m,1h,2h,6h,15h）；
					echo 'success';exit();
				}
				echo 'error';exit();
			}
			echo 'error';exit();
		}else{
			echo 'error';exit();
		}
		exit();
    }
	
	//处理订单逻辑
	private function _doOrder($m)
	{
		if($m['paymethod']=='zfbf2f'){
			
			
		}
		
	}

}