<?php
/**
 * File: zfbf2f.php
 * Functionality: 支付宝面对面支付
 * Author: 资料空白
 * Date: 2018-6-8
 */
namespace Pay;

use \Payment\Client\Charge;
use \Payment\Notify\PayNotifyInterface;
use \Payment\Common\PayException;
use \Payment\Config;

class zfbf2f implements PayNotifyInterface
{
	//处理请求
	public function pay($payconfig,$params)
	{
		$config = [
			'use_sandbox' => false,
			'app_id' => $payconfig['app_id'],
			'sign_type' => $payconfig['sign_type'],
			'ali_public_key' => $payconfig['ali_public_key'],
			'rsa_private_key' => $payconfig['rsa_private_key'],
			'notify_url' => $params['web_url'] . $payconfig['notify_url'],
			'return_url' =>$params['web_url']. $payconfig['notify_url'].'?paymethod='.$payconfig['alias'].'&orderid='.$params['orderid'],
			'return_raw' => true
		];

		$data = [
			'order_no' => $params['orderid'],
			'amount' => $params['money'],
			'subject' => $params['productname'],
			'body' => 'zfbf2f', 
		];
		try {
			$str = Charge::run(Config::ALI_CHANNEL_QR, $config, $data);
			return array('code'=>1,'msg'=>'success','data'=>$str);
		} catch (PayException $e) {
			return array('code'=>1000,'msg'=>$e->errorMessage(),'data'=>'');
		}
	}
	
	//处理返回
	public function notifyProcess(array $params)
	{
		$m_order =  \Helper::load('order');
		$m_products_card = \Helper::load('products_card');
		$m_email_queue = \Helper::load('email_queue');
		$m_products = \Helper::load('products');
		$m_config = \Helper::load('config');
		$web_config = $m_config->getConfig();
		
		try{
			if($params['body']=='zfbf2f'){
				//1.先更新支付总金额
				$update = array('status'=>1,'paytime'=>time(),'tradeid'=>$params['trade_no'],'paymethod'=>$params['body'],'paymoney'=>$params['total_amount']);
				$u = $m_order->Where(array('orderid'=>$params['out_trade_no'],'status'=>0))->Update($update);
				if(!$u){
					$data =array('code'=>1004,'msg'=>'更新失败');
				}else{
					//2.开始进行订单处理
					//通过orderid,查询order订单,与商品信息
					$order = $m_order->Where(array('orderid'=>$params['out_trade_no']))->SelectOne();
					$product = $m_products->SelectByID('auto,stockcontrol',$order['pid']);
					
					if(!empty($order) AND !empty($product)){
						if($product['auto']>0){
							//3.自动处理
							//查询通过订单中记录的pid，根据购买数量查询卡密
							$cards = $m_products_card->Where(array('pid'=>$order['pid'],'oid'=>0))->Limit($order['number'])->Select();
							if(is_array($cards) AND !empty($cards) AND count($cards)==$order['number']){
								//3.1 库存充足,获取对应的卡id,卡密
								$card_mi_array = array_column($cards, 'card');
								$card_mi_str = implode(',',$card_mi_array);
								$card_id_array = array_column($cards, 'id');
								$card_id_str = implode(',',$card_id_array);
								//3.1.2 进行卡密处理,如果进行了库存控制，就开始处理
								if($product['stockcontrol']>0){
									//3.1.2.1 直接进行卡密与订单的关联
									$m_products_card->Where("id in ({$card_id_str})")->Where(array('oid'=>0))->Update(array('active'=>1));
									//3.1.2.2 然后进行库存清减
									$qty_m = array('qty' => 'qty-'.$order['number']);
									$m_products->Where(array('id'=>$order['pid'],'stockcontrol'=>1))->Update($qty_m,TRUE);
								}else{
									//3.1.2.3不进行库存控制时,自动发货商品是不需要减库存，也不需要取消卡密；因为这种情况下的卡密是通用的；
								}
								//3.1.3 更新订单状态,同时把卡密写到订单中
								$m_order->Where(array('orderid'=>$params['out_trade_no'],'status'=>1))->Update(array('status'=>2,'kami'=>$card_mi_str));
								//3.1.4 把邮件通知写到消息队列中，然后用定时任务去执行即可
								$m = array();
								//3.1.4.1通知用户,定时任务去执行
								$content = '用户:' . $order['email'] . ',购买的商品['.$order['productname'].'],卡密是:'.$card_mi_str;
								$m=array('email'=>$order['email'],'subject'=>'商品购买成功','content'=>$content,'addtime'=>time(),'status'=>0);
								//3.1.4.2通知管理员,定时任务去执行
								$content = '用户:' . $order['email'] . ',购买的商品['.$order['productname'].'],卡密发送成功';
								$m=array('email'=>$web_config['admin_email'],'subject'=>'用户购买商品','content'=>$content,'addtime'=>time(),'status'=>0);
								$m_email_queue->MultiInsert($m);
								$data =array('code'=>1,'msg'=>'自动发卡');
							}else{
								//3.2 这里说明库存不足了，干脆就什么都不处理，直接记录异常，同时更新订单状态
								$m_order->Where(array('orderid'=>$params['out_trade_no'],'status'=>1))->Update(array('status'=>3));
								file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.'库存不足，无法处理'.PHP_EOL, FILE_APPEND);
								//3.2.3邮件通知写到消息队列中，然后用定时任务去执行即可
								$m = array();
								//3.2.3.1通知用户,定时任务去执行
								$content = '用户:' . $order['email'] . ',购买的商品['.$order['productname'].'],由于库存不足暂时无法处理,管理员正在拼命处理中....请耐心等待!';
								$m[] = array('email'=>$order['email'],'subject'=>'商品购买成功','content'=>$content,'addtime'=>time(),'status'=>0);
								//3.2.3.2通知管理员,定时任务去执行
								$content = '用户:' . $order['email'] . ',购买的商品['.$order['productname'].'],由于库存不足暂时无法处理,请尽快处理!';
								$m[] = array('email'=>$web_config['admin_email'],'subject'=>'用户购买商品','content'=>$content,'addtime'=>time(),'status'=>0);
								$m_email_queue->MultiInsert($m);
								$data =array('code'=>1005,'msg'=>'库存不足,无法处理');
							}
						}else{
							//4.手工操作
							//4.1如果商品有进行库存控制，就减库存
							if($product['stockcontrol']>0){
								$qty_m = array('qty' => 'qty-'.$order['number']);
								$m_products->Where(array('id'=>$order['pid'],'stockcontrol'=>1))->Update($qty_m,TRUE);
							}
							//4.2邮件通知写到消息队列中，然后用定时任务去执行即可
							$m = array();
							//4.2.1通知用户,定时任务去执行
							$content = '用户:' . $order['email'] . ',购买的商品['.$order['productname'].'],属于手工发货类型，管理员即将联系您....请耐心等待!';
							$m[] = array('email'=>$order['email'],'subject'=>'商品购买成功','content'=>$content,'addtime'=>time(),'status'=>0);
							//4.2.2通知管理员,定时任务去执行
							$content = '用户:' . $order['email'] . ',购买的商品['.$order['productname'].'],属于手工发货类型，请尽快联系他!';
							$m[] = array('email'=>$web_config['admin_email'],'subject'=>'用户购买商品','content'=>$content,'addtime'=>time(),'status'=>0);
							$m_email_queue->MultiInsert($m);
							$data =array('code'=>1,'msg'=>'手工订单');
						}
					}else{
						//这里有异常，到时统一记录处理
						$data =array('code'=>1003,'msg'=>'订单/商品不存在');
					}
				}	
			}else{
				$data =array('code'=>1002,'msg'=>'支付方式不对');
			}
		} catch(\Exception $e) {
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.$e->getMessage().PHP_EOL, FILE_APPEND);
			$data =array('code'=>1001,'msg'=>$e->getMessage());
		}
		//file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.'异步处理结果:'.json_encode($data).PHP_EOL, FILE_APPEND);
		return $data;
	}
	
}