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
    public function init()
    {
        parent::init();
		$this->m_products = $this->load('products');
		$this->m_order = $this->load('order');
    }

    public function buyAction()
    {
		//下订单
		$pid = $this->getPost('productlist');
		$number = $this->getPost('number');
		$email = $this->getPost('email');
		$chapwd = $this->getPost('chapwd');
		if(is_numeric($pid) AND $pid>0 AND is_numeric($number) AND $number>0 AND $email AND isEmail($email) AND $chapwd){
			$product = $this->m_products->Where(array('id'=>$pid))->SelectOne();
			if(!empty($product)){
				if($product['stockcontrol']==1 AND $product['qty']<1){
					$data = array('code' => 1002, 'msg' => '库存不足');
				}else{
					//进行同一ip，下单未付款的处理判断
					$myip = getClientIP();
					$total = $this->m_order->Where(array('ip'=>$myip,'status'=>0))->Total();
					if($total>1){
						$m=array(
							'userid'=>$this->userid,
							'email'=>$email,
							'number'=>$number,
							'productname'=>$product['name'],
							'pid'=>$pid,
							'addtime'=>time(),
							'ip'=>$myip,
							'status'=>0,
							'chapwd'=>$chapwd,
							'money'=>$product['price']*$number,
						);
						$id=$this->m_order->Insert($m);
						$data = array('code' => 1, 'msg' => '下单成功','data'=>array('orderid'=>$id));
					}else{
						$data = array('code' => 1003, 'msg' => '处理失败,您有太多未付款订单了');
					}
				}
			}else{
				$data = array('code' => 1001, 'msg' => '商品不存在');
			}
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
    }
	
	public function payAction()
	{
		$data = array();
        $this->getView()->assign($data);
	}
}