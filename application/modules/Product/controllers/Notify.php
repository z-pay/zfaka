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
	private $m_products_card;
	private $m_email_queue;
	private $m_products;
    public function init()
    {
        parent::init();
		$this->m_order = $this->load('order');
		$this->m_payment = $this->load('payment');
		$this->m_products_card = $this->load('products_card');
		$this->m_email_queue = $this->load('email_queue');
		$this->m_products = $this->load('products');
    }

    public function indexAction()
    {
		file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.json_encode($_POST).PHP_EOL, FILE_APPEND);
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
					$params = array('paymethod'=>$paymethod,'tradeid'=>$_POST['trade_no'],'orderid'=>$_POST['out_trade_no'],'paymoney'=>$_POST['total_amount']);
					$this->_doOrder($params);
					//处理你的逻辑，例如获取订单号$_POST['out_trade_no']，订单金额$_POST['total_amount']等
					//程序执行完后必须打印输出“success”（不包含引号）。如果商户反馈给支付宝的字符不是success这7个字符，支付宝服务器会不断重发通知，直到超过24小时22分钟。一般情况下，25小时以内完成8次通知（通知的间隔频率一般是：4m,10m,10m,1h,2h,6h,15h）；
					echo 'success';exit();
				}else{
					echo 'error';exit();
				}
			}else{
				echo 'error';exit();
			}
		}else{
			echo 'error';exit();
		}
    }
	
	//处理订单逻辑
	private function _doOrder($params)
	{
		try{
			if($params['paymethod']=='zfbf2f'){
				//1.先更新支付总金额
				$update = array('status'=>1,'paytime'=>time(),'tradeid'=>$params['tradeid'],'paymethod'=>$params['paymethod'],'paymoney'=>$params['paymoney']);
				$this->m_order->Where(array('orderid'=>$params['orderid'],'status'=>0))->Update($update);
				
				//2.检查是否属于自动发卡产品,如果是就自动发卡
				//---2.1通过orderid,查询order订单
				$order = $this->m_order->Where(array('orderid'=>$params['orderid']))->SelectOne();
				if(!empty($order)){
					if($order['auto']>0){
						//自动处理
						//2.2查询通过订单中记录的pid，根据购买数量查询卡密
						$cards = $this->m_products_card->Where(array('pid'=>$order['pid'],'oid'=>0))->Limit($order['number'])->Select();
						if(is_array($cards) AND !empty($cards) AND count($cards)==$order['number']){
							//2.3已经获取到了对应的卡id,卡密
							$card_mi_array = array_column($cards, 'card');
							$card_mi_str = implode(',',$card_mi_array);
							
							$card_id_array = array_column($cards, 'card');
							$card_id_str = implode(',',$card_id_array);						
							
							//2.4直接进行卡密与订单的关联
							$this->m_order->Where("id in ({$card_id_str})")->Where(array('oid'=>0))->Update(array('oid'=>$order['id']));
							//2.5然后进行库存清减
							$qty_m = array('qty' => 'qty-'.$order['number']);
							$this->m_products->Where(array('id'=>$order['pid']))->Update($qty_m,TRUE);	
							//2.6 把邮件通知写到消息队列中，然后用定时任务去执行即可
							$content = '用户:' . $email . ',购买的产品'.$order['productname'].',卡密是:'.$card_id_str;
							$m=array('email'=>$order['email'],'subject'=>'卡密发送','content'=>$content,'addtime'=>time(),'status'=>0);
							$this->m_email_queue->Insert($m);
						}else{
							//这里说明库存不足了，干脆就什么都不处理，直接记录异常，同时更新订单状态
							$this->m_order->Where(array('orderid'=>$params['orderid'],'status'=>1))->Update(array('status'=>3));
							file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.'库存不足，无法处理'.PHP_EOL, FILE_APPEND);
							//把邮件通知写到消息队列中，然后用定时任务去执行即可
							$content = '用户:' . $email . ',购买的产品'.$order['productname'].',由于库存不足暂时无法处理,管理员正在拼命处理中....请耐心等待!';
							$m=array('email'=>$order['email'],'subject'=>'卡密发送','content'=>$content,'addtime'=>time(),'status'=>0);
							$this->m_email_queue->Insert($m);
						}
					}else{
						//手工操作，这里暂时不处理	
					}
				}else{
					//这里有异常，到时统一记录处理
				}
			}
		} catch(\Exception $e) {
			file_put_contents(YEWU_FILE, CUR_DATETIME.'-'.$e->getMessage().PHP_EOL, FILE_APPEND);
		}
	}

}