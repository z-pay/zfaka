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
use \PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

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
							'return_url' => $params['weburl']. "/product/order/payjump?paymethod=paypal&orderid={$params['orderid']}",
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
			if ($response->statusCode == 201){
				foreach($response->result->links as $link)
				{
					if($link->rel == "approve"){
						$url = $link->href;
						break; 
					}
				}
				//更新paypal的支付id，到数据库
				$m_order =  \Helper::load('order');
				$m_order->Where(array('orderid'=>$params['orderid'],'status'=>0))->Update(array('configure1'=>$response->result->id));
				
				$result = array('type'=>1,'subjump'=>0,'paymethod'=>$this->paymethod,'url'=>$url,'payname'=>$payconfig['payname'],'overtime'=>$payconfig['overtime'],'money'=>$params['money']);
				return array('code'=>1,'msg'=>'success','data'=>$result);
			}else{
				return array('code'=>1000,'msg'=>"失败",'data'=>'');
			}
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
	
	//处理回调
	public function jump($payconfig,$params)
	{
		try{
			$request = new OrdersCaptureRequest($params['order']['configure1']);

			if($payconfig['configure3']=="live"){
				$environment = new ProductionEnvironment($payconfig['app_id'], $payconfig['app_secret']);
			}else{
				$environment = new SandboxEnvironment($payconfig['app_id'], $payconfig['app_secret']);
			}
				
			$client = new PayPalHttpClient($environment);
			$response = $client->execute($request);
			print_r($response);exit();
			
			if ($response->statusCode == 201)
			{
				
			}else{
				
			}
			header("location:/query/auto/{$params['orderid']}.html");
			exit();
		} catch (\Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}
}
