<?php
use \Pay\zfbf2f;
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
		$callback = new \Pay\zfbf2f();
		$str='{"paymethod":"zfbf2f","gmt_create":"2018-06-09 14:55:38","charset":"UTF-8","seller_email":"18175617527","subject":"\u9080\u8bf7\u7801-\u5168\u7403\u4e3b\u673a\u4ea4\u6d41\u8bba\u575b","sign":"jh32zL7TZvUJNAR8Ycz6RTEEuYCh6R3x6QYu496JTQZ+MK+58VeETshXUmjmgaQ1REB+mN\/lIbbyNSSxwBVyDWDhZ\/X\/k6\/l2EtFtHOTIV4bjD2fWEQ9035x+f+IunKypyEUSM4RnvoZRGMehWEzJ5gD+m4A0opQP3mkiy5ULpTvoKSLjOKr6Rp7GcJD6JEx01NOsGjGZCA2PcbCEYVXlxENUJH172uAsR4KcpG7GLL8KFhZO6pKO3dmlgcfg1tCIU69GibhCqups9sqkZOm6aw+0sXRYf84Pxfz8E5ssGbCpdgChztqyD09b6Az05F0EQ8BJTaV970IhEBi7sKIlQ==","body":"zfbf2f","buyer_id":"2088902956748723","invoice_amount":"2.00","notify_id":"ae1ef7760db261707e66d5cc65f21cblk5","fund_bill_list":"[{\"amount\":\"2.00\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notify_type":"trade_status_sync","trade_status":"TRADE_SUCCESS","receipt_amount":"2.00","buyer_pay_amount":"2.00","app_id":"2018060660307830","sign_type":"RSA2","seller_id":"2088032348049281","gmt_payment":"2018-06-09 14:55:49","notify_time":"2018-06-10 15:19:17","version":"1.0","out_trade_no":"zlkb2018060914524334805","total_amount":"2.00","trade_no":"2018060921001004720547186083","auth_app_id":"2018060660307830","buyer_logon_id":"137****8591","point_amount":"0.00"}';
		$data= json_decode($str,true);
		$result=$callback->notifyProcess($data);
		print_r($result);
		exit();
	}
}