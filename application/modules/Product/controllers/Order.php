<?php

/*
 * 功能：会员中心－个人中心
 * author:资料空白
 * time:20180509
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
		$pid = $this->getPost('productlist');
		$number = $this->getPost('number');
		$email = $this->getPost('email');
		$chapwd = $this->getPost('chapwd');
		$csrf_token = $this->getPost('csrf_token', false);
		
		if(is_numeric($pid) AND $pid>0 AND is_numeric($number) AND $number>0 AND $email AND isEmail($email) AND $chapwd AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$product = $this->m_products->Where(array('id'=>$pid))->SelectOne();
				if(!empty($product)){
					//库存控制
					if($product['stockcontrol']==1 AND $product['qty']<1){
						$data = array('code' => 1002, 'msg' => '库存不足');
						Helper::response($data);
					}
					if($product['stockcontrol']==1 AND $number>$product['qty']){
						$data = array('code' => 1002, 'msg' => '库存不足');
						Helper::response($data);
					}
						
					//进行同一ip，下单未付款的处理判断
					$myip = getClientIP();
					$total = $this->m_order->Where(array('ip'=>$myip,'status'=>0))->Total();
					if($total>1){
						$data = array('code' => 1003, 'msg' => '处理失败,您有太多未付款订单了');
						Helper::response($data);
					}
					
					//进行同一email，下单未付款的处理判断
					$total = $this->m_order->Where(array('email'=>$email,'status'=>0))->Total();
					if($total>1){
						$data = array('code' => 1003, 'msg' => '处理失败,您有太多未付款订单了');
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
					
					//开始下单，入库
					$m=array(
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
					$orderid = base64_encode($id);
					$data = array('code' => 1, 'msg' => '下单成功','data'=>array('orderid'=>$orderid));
				}else{
					$data = array('code' => 1001, 'msg' => '商品不存在');
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
		$orderid = $this->get('oid',false);
		$orderid = (int)base64_decode($orderid);
		if(is_numeric($orderid) AND $orderid>0){
			$order = $this->m_order->Where(array('id'=>$orderid,'status'=>0))->SelectOne();
			if(!empty($order)){
				//获取支付方式
				$payments = $this->m_payment->getConfig();
				$data['order']=$order;
				$data['payments']=$payments;
				$data['code']=1;
			}else{
				$data['code']=1002;
				$data['msg']='订单不存在/订单已支付';
			}
		}else{
			$data['code']=1001;
			$data['msg']='订单不存在';
		}
		$this->getView()->assign($data);
	}
	
	public function payajaxAction()
	{
		$paymethod = $this->getPost('paymethod');
		$orderid = $this->getPost('orderid');
		$csrf_token = $this->getPost('csrf_token');
		if($paymethod AND $orderid AND $csrf_token){
			$payments = $this->m_payment->getConfig();
			if(isset($payments[$paymethod]) AND !empty($payments[$paymethod])){
				$payconfig = $payments[$paymethod];
				if($payconfig['active']>0){
					//获取订单信息
					$order = $this->m_order->Where(array('id'=>$orderid))->SelectOne();
					if(is_array($order) AND !empty($order)){
						if($order['status']>0){
							$data = array('code' => 1004, 'msg' => '订单已支付成功');
						}else{
							$zfbf2f = new \Pay\zfbf2f();
							$params =array('orderid'=>$order['id'],'money'=>$order['money'],'productname'=>$order['productname']);
							$data = $zfbf2f->pay($payconfig,$params);
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
	public function showqrAction(){
		$url = $this->get('url');
		if($url AND filter_var($url, FILTER_VALIDATE_URL)){
			\PHPQRCode\QRcode::png($url);
			exit();
		}else{
			echo '';
		}
	}
}