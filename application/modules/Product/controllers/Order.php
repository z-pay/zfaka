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

    public function indexAction()
    {
		//下订单
		$pid = $this->getPost('pid');
		$number = $this->getPost('number');
		$email = $this->getPost('email');
		if(is_numeric($pid) AND $pid>0 AND is_numeric($number) AND $number>0 AND $email AND isEmail($email)){
			$product = $this->m_products->Where(array('id'=>$pid))->SelectOne();
			if(!empty($product)){
				if($product['stockcontrol']==1 AND $product['qty']>0){
					$m=array(
						'userid'=>$this->userid,
						'email'=>$email,
						'pid'=>$pid,
						'addtime'=>time()
					);
					$this->m_order->Insert($m);
					$data = array('code' => 1, 'msg' => '下单成功');
				}else{
					$data = array('code' => 1002, 'msg' => '库存不足');
				}
			}else{
				$data = array('code' => 1001, 'msg' => '商品不存在');
			}
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
    }
}