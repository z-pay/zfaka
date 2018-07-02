<?php

/*
 * 功能：会员中心－个人中心
 * Author:资料空白
 * Date:20180509
 */

class OrderController extends PcBasicController
{
	private $m_products;
	private $m_order;
	private $m_user;
	private $m_payment;
	
    public function init()
    {
        parent::init();
		$this->m_products = $this->load('products');
		$this->m_order = $this->load('order');
		$this->m_user = $this->load('user');
		$this->m_payment = $this->load('payment');
    }

    public function buyAction()
    {
		//下订单
		$pid = ceil($this->getPost('productlist'));
		$number = ceil($this->getPost('number'));
		$email = $this->getPost('email');
		$chapwd = $this->getPost('chapwd');
		$csrf_token = $this->getPost('csrf_token', false);
		
		if(is_numeric($pid) AND $pid>0 AND is_numeric($number) AND $number>0 AND $email AND isEmail($email) AND $chapwd AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$product = $this->m_products->Where(array('id'=>$pid))->SelectOne();
				if(!empty($product)){
					$myip = getClientIP();
					
					//库存控制
					if($product['stockcontrol']==1 AND $product['qty']<1){
						$data = array('code' => 1003, 'msg' => '库存不足');
						Helper::response($data);
					}
					if($product['stockcontrol']==1 AND $number>$product['qty']){
						$data = array('code' => 1004, 'msg' => '库存不足');
						Helper::response($data);
					}
					
					$starttime = strtotime(date("Y-m-d"));
					$endtime = strtotime(date("Y-m-d 23:59:59"));
					//进行同一ip，下单未付款的处理判断
					if(isset($this->config['limit_ip_order']) AND $this->config['limit_ip_order']>0){
						$total = $this->m_order->Where(array('ip'=>$myip,'status'=>0))->Where("addtime>={$starttime} and addtime<={$endtime}")->Total();
						if($total>$this->config['limit_ip_order']){
							$data = array('code' => 1005, 'msg' => '处理失败,您有太多未付款订单了');
							Helper::response($data);
						}
					}

					//进行同一email，下单未付款的处理判断
					if(isset($this->config['limit_email_order']) AND $this->config['limit_email_order']>0){
						$total = $this->m_order->Where(array('email'=>$email,'status'=>0))->Where("addtime>={$starttime} and addtime<={$endtime}")->Total();
						if($total>$this->config['limit_email_order']){
							$data = array('code' => 1006, 'msg' => '处理失败,您有太多未付款订单了');
							Helper::response($data);
						}
					}
					
					//进行同一商品，禁止重复下单的判断
					$total = $this->m_order->Where(array('email'=>$email,'status'=>0,'pid'=>$pid))->Where("addtime>={$starttime} and addtime<={$endtime}")->Total();
					if($total>0){
						$data = array('code' => 1007, 'msg' => '处理失败,商品限制重复下单,请直接查询订单进行支付');
						Helper::response($data);
					}
					
					//记录用户uid
					if($this->login AND $this->userid){
						$userid = $this->userid;
					}else{
						$uinfo = $this->m_user->Where(array('email'=>$email))->SelectOne();
						if(!empty($uinfo)){
							$userid = $uinfo['id'];
						}else{
							$userid = 0;
						}
					}
					
					//生成orderid
					$orderid = 'zlkb' . date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . mt_rand(10000, 99999);
					
					//开始下单，入库
					$m=array(
						'orderid'=>$orderid,
						'userid'=>$userid,
						'email'=>$email,
						'pid'=>$pid,
						'productname'=>$product['name'],
						'price'=>$product['price'],
						'number'=>$number,
						'money'=>$product['price']*$number,
						'chapwd'=>$chapwd,
						'ip'=>$myip,
						'status'=>0,
						'addtime'=>time(),
					);
					$id=$this->m_order->Insert($m);
					if($id>0){
						$oid = base64_encode($id);
						$data = array('code' => 1, 'msg' => '下单成功','data'=>array('oid'=>$oid));	
					}else{
						$data = array('code' => 1003, 'msg' => '订单异常');
					}
				}else{
					$data = array('code' => 1002, 'msg' => '商品不存在');
				}
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
    }
	
	public function payAction()
	{
		$data = array();
		$oid = $this->get('oid',false);
		$oid = (int)base64_decode($oid);
		if(is_numeric($oid) AND $oid>0){
			$order = $this->m_order->Where(array('id'=>$oid))->SelectOne();
			if(!empty($order)){
				//获取支付方式
				$payments = $this->m_payment->getConfig();
				$data['order']=$order;
				$data['payments']=$payments;
				$data['code']=1;
			}else{
				$data['code']=1002;
				$data['msg']='订单不存在';
			}
		}else{
			$data['code']=1001;
			$data['msg']='订单不存在';
		}
		$data['title'] = "订单支付";
		$this->getView()->assign($data);
	}
	
	public function payajaxAction()
	{
		$paymethod = $this->getPost('paymethod');
		$oid = $this->getPost('oid');
		$csrf_token = $this->getPost('csrf_token');
		if($paymethod AND $oid AND $csrf_token){
			$payments = $this->m_payment->getConfig();
			if(isset($payments[$paymethod]) AND !empty($payments[$paymethod])){
				$payconfig = $payments[$paymethod];
				if($payconfig['active']>0){
					//获取订单信息
					$order = $this->m_order->Where(array('id'=>$oid))->SelectOne();
					if(is_array($order) AND !empty($order)){
						if($order['status']>0){
							$data = array('code' => 1004, 'msg' => '订单已支付成功');
						}else{
							try{
								$payclass = "\\Pay\\".$paymethod."\\".$paymethod;
								$PAY = new $payclass();
								$params =array('orderid'=>$order['orderid'],'money'=>$order['money'],'productname'=>$order['productname'],'web_url'=>$this->config['web_url']);
								$data = $PAY->pay($payconfig,$params);
							} catch (\Exception $e) {
								$data = array('code' => 1005, 'msg' => $e->errorMessage());
							}
						}
					}else{
						$data = array('code' => 1003, 'msg' => '订单不存在');
					}
				}else{
					$data = array('code' => 1002, 'msg' => '支付渠道已关闭');
				}
			}else{
				$data = array('code' => 1001, 'msg' => '支付渠道异常');
			}
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}
	
	//支付宝当面付生成二维码
	public function showqrAction()
	{
		$url = $this->get('url');
		if($url){
			\PHPQRCode\QRcode::png($url);
			exit();
		}else{
			echo '';
			exit();
		}
	}
}