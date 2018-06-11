<?php
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
			'notify_url' => SITE_URL . $payconfig['notify_url'],
			'return_url' =>SITE_URL. $payconfig['notify_url'].'?paymethod='.$payconfig['alias'].'&orderid='.$params['orderid'],
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
		
		try{
			if($params['body']=='zfbf2f'){
				//1.先更新支付总金额
				$update = array('status'=>1,'paytime'=>time(),'tradeid'=>$params['transaction_id'],'paymethod'=>$params['body'],'paymoney'=>$params['amount']);
				$u = $m_order->Where(array('orderid'=>$params['order_no'],'status'=>0))->Update($update);
				if(!$u){
					return $data =array('code'=>1004,'msg'=>'更新失败');
				}
				//2.检查是否属于自动发卡产品,如果是就自动发卡
				//---2.1通过orderid,查询order订单
				$order = $m_order->Where(array('orderid'=>$params['order_no']))->SelectOne();
				if(!empty($order)){
					if($order['auto']>0){
						//自动处理
						//2.2查询通过订单中记录的pid，根据购买数量查询卡密
						$cards = $m_products_card->Where(array('pid'=>$order['pid'],'oid'=>0))->Limit($order['number'])->Select();
						if(is_array($cards) AND !empty($cards) AND count($cards)==$order['number']){
							//2.3已经获取到了对应的卡id,卡密
							$card_mi_array = array_column($cards, 'card');
							$card_mi_str = implode(',',$card_mi_array);
							$card_id_array = array_column($cards, 'id');
							$card_id_str = implode(',',$card_id_array);
							//2.4直接进行卡密与订单的关联
							$m_products_card->Where("id in ({$card_id_str})")->Where(array('oid'=>0))->Update(array('oid'=>$order['id']));
							//2.5然后进行库存清减
							$qty_m = array('qty' => 'qty-'.$order['number']);
							$m_products->Where(array('id'=>$order['pid']))->Update($qty_m,TRUE);
							//2.6更新订单状态
							$m_order->Where(array('orderid'=>$params['order_no'],'status'=>1))->Update(array('status'=>2));
							//2.7 把邮件通知写到消息队列中，然后用定时任务去执行即可
							$content = '用户:' . $order['email'] . ',购买的产品['.$order['productname'].'],卡密是:'.$card_mi_str;
							$m=array('email'=>$order['email'],'subject'=>'卡密发送','content'=>$content,'addtime'=>time(),'status'=>0);
							$m_email_queue->Insert($m);
							$data =array('code'=>1,'msg'=>'自动发卡');
						}else{
							//这里说明库存不足了，干脆就什么都不处理，直接记录异常，同时更新订单状态
							$m_order->Where(array('orderid'=>$params['order_no'],'status'=>1))->Update(array('status'=>3));
							file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.'库存不足，无法处理'.PHP_EOL, FILE_APPEND);
							//把邮件通知写到消息队列中，然后用定时任务去执行即可
							$content = '用户:' . $order['email'] . ',购买的产品['.$order['productname'].'],由于库存不足暂时无法处理,管理员正在拼命处理中....请耐心等待!';
							$m=array('email'=>$order['email'],'subject'=>'卡密发送','content'=>$content,'addtime'=>time(),'status'=>0);
							$m_email_queue->Insert($m);
							$data =array('code'=>1005,'msg'=>'库存不足，无法处理');
						}
					}else{
						//手工操作，这里暂时不处理
						$data =array('code'=>1004,'msg'=>'手工订单，不处理');
					}
				}else{
					//这里有异常，到时统一记录处理
					$data =array('code'=>1003,'msg'=>'订单不存在');
				}
			}else{
				$data =array('code'=>1002,'msg'=>'支付方式不对');
			}
		} catch(\Exception $e) {
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.$e->getMessage().PHP_EOL, FILE_APPEND);
			$data =array('code'=>1001,'msg'=>$e->getMessage());
		}
		file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.'异步处理结果:'.json_encode($data).PHP_EOL, FILE_APPEND);
		return $data;
	}
	
}