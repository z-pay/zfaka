<?php
/**
 * File: paypal.php
 * Functionality: paypal支付
 * Author: 资料空白
 * Date: 2019-05-27
 */
namespace Pay\paypal;
use \Pay\paypal\PaypalIPN;

use \PayPal\Api\Amount;
use \PayPal\Api\Details;
use \PayPal\Api\Item;
use \PayPal\Api\ItemList;
use \PayPal\Api\Payer;
use \PayPal\Api\Payment;
use \PayPal\Api\RedirectUrls;
use \PayPal\Api\Transaction;
use \PayPal\Rest\ApiContext;
class paypal
{
	private $paymethod = "paypal";
	
	//处理请求
	public function pay($payconfig,$params)
	{
		try{
			$apiContext = new ApiContext(
				new OAuthTokenCredential(
					$payconfig['app_id'],
					$payconfig['app_secret']
				)
			);

			$apiContext->setConfig(
				array(
					'mode' => $payconfig['configure3'],
					'log.LogEnabled' => false,
					'cache.enabled' => false,
					//'http.CURLOPT_CONNECTTIMEOUT' => 30
				)
			);
			
			
			$payer = new Payer();
			$payer->setPaymentMethod("paypal");
			
			$item1 = new Item();
			$item1->setName($params['productname'])
				->setCurrency('USD')
				->setQuantity(1)
				->setPrice($params['money']);
			$itemList = new ItemList();
			$itemList->setItems(array($item1));			
			
			$details = new Details();
			$details->setSubtotal($params['money']);
			
			$amount = new Amount();
			$amount->setCurrency("USD")
				->setTotal(1)
				->setDetails($details);			
			
			$transaction = new Transaction();
			$transaction->setAmount($amount)
				->setItemList($itemList)
				->setDescription("亲，请请核对以下付款信息:")
				->setInvoiceNumber($params['orderid']);			
			
			$baseUrl = getBaseUrl();
			$redirectUrls = new RedirectUrls();
			$redirectUrls->setReturnUrl($params['weburl']. "/query/auto/{$params['orderid']}.html")
				->setCancelUrl($params['weburl']);
	
			$payment = new Payment();
			$payment->setIntent("order")
				->setPayer($payer)
				->setRedirectUrls($redirectUrls)
				->setTransactions(array($transaction));
				
			$payment->create($apiContext);	
			$url = $payment->getApprovalLink();	
	
			$result = array('type'=>1,'subjump'=>0,'paymethod'=>$this->paymethod,'url'=>$url,'payname'=>$payconfig['payname'],'overtime'=>$payconfig['overtime'],'money'=>$params['money']);
			return array('code'=>1,'msg'=>'success','data'=>$result);
		} catch (\Exception $e) {
			return array('code'=>1000,'msg'=>$e->getMessage(),'data'=>'');
		}
	}
	
	
	//处理返回
	public function notify($payconfig)
	{
		file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($_POST).PHP_EOL, FILE_APPEND);
		
		$ipn = new \Pay\paypal\PaypalIPN();

		if($payconfig['configure3']=="sandbox"){
			$ipn->useSandbox();
		}
		
		$verified = $ipn->verifyIPN();
		
		if ($verified) {
			/*invoice
			
			//业务处理
			$config = array('paymethod'=>$this->paymethod,'tradeid'=>$params['pay_no'],'paymoney'=>$params['money'],'orderid'=>$params['pay_id'] );
			$notify = new \Pay\notify();
			$data = $notify->run($config);
			if($data['code']>1){
				return 'error|Notify: '.$data['msg'];
			}else{
				return 'success';
			}
			*/
			header("HTTP/1.1 200 OK");
			echo  'success';
			exit;
		}else{
			
		}
	}
}
