<?php
/**
 * File: paypal.php
 * Functionality: paypal支付
 * Author: 资料空白
 * Date: 2019-05-27
 */
namespace Pay\paypal;
use \Pay\paypal\PaypalIPN;

use \PayPalCheckoutSdk\Core\PayPalHttpClient;
use \PayPalCheckoutSdk\Core\SandboxEnvironment;
use \PayPalCheckoutSdk\Core\ProductionEnvironment;

use \PayPalCheckoutSdk\Orders\OrdersCreateRequest;


class paypal
{
	private $paymethod = "paypal";
	
	//处理请求
	public function pay($payconfig,$params)
	{
		try{
			
			if($payconfig['configure3']=="live"){
				$environment = new ProductionEnvironment($payconfig['app_id'], $payconfig['app_secret']);
			}else{
				$environment = new SandboxEnvironment($payconfig['app_id'], $payconfig['app_secret']);
			}
			
			$client = new PayPalHttpClient($environment);
			
			$request = new OrdersCreateRequest();
			$request->headers["prefer"] = "return=minimal";
			$request->body = array(
					'intent' => 'CAPTURE',
					'application_context' =>
						array(
							'return_url' => $params['weburl']. "/query/auto/{$params['orderid']}.html",
							'cancel_url' => $params['weburl'],
							'brand_name' => $params['webname'],
							'locale' => 'zh-CN',
							'landing_page' => 'BILLING',
							'shipping_preferences' => 'NO_SHIPPING',
							'user_action' => 'PAY_NOW',
						),
					'purchase_units' =>
						array(
							0 =>
								array(
									'invoice_id'=>"{$params['orderid']}",
									'description' => '亲，请请核对以下付款信息:',
									'soft_descriptor' => "{$params['productname']}",
									'amount' =>
										array(
											'currency_code' => 'USD',
											'value' => "{$params['money']}",
											'breakdown' =>
												array(
													'item_total' =>
														array(
															'currency_code' => 'USD',
															'value' => "{$params['money']}",
														),
												),
										),
									'items' =>
										array(
											0 =>
												array(
													'name' => "{$params['productname']}",
													'unit_amount' =>
														array(
															'currency_code' => 'USD',
															'value' => "{$params['money']}",
														),
													'quantity' => '1',
												)
										),
								),
						),
				);

			
			$response = $client->execute($request);
			foreach($response->result->links as $link)
			{
				if($link->rel == "approve"){
					$url = $link->href;
					break; 
				}
			}
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
		
		try{
			$ipn = new \Pay\paypal\PaypalIPN();

			if($payconfig['configure3']=="sandbox"){
				$ipn->useSandbox();
			}
			
			$verified = $ipn->verifyIPN();
			
			if ($verified) {
				
				//业务处理
				$config = array('paymethod'=>$this->paymethod,'tradeid'=>$params['txn_id'],'paymoney'=>$params['mc_gross'],'orderid'=>$params['invoice'] );
				$notify = new \Pay\notify();
				$data = $notify->run($config);
				if($data['code']>1){
					return 'error|Notify: '.$data['msg'];
				}else{
					return 'success';
				}
				
				header("HTTP/1.1 200 OK");
				echo  'success';
				exit;
			}else{
				
			}
		} catch (\Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}
