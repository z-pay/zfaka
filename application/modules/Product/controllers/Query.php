<?php

/*
 * 功能：会员中心－个人中心
 * Author:资料空白
 * Date:20180509
 */

class QueryController extends PcBasicController
{
	private $m_order;
    public function init()
    {
        parent::init();
		$this->m_order = $this->load('order');
    }

    public function indexAction()
    {
		$data = array();
		$orderid  = $this->get('orderid',false);
		if($orderid){
			$data['order'] = $data['cnstatus'] = array();
			$order_email = $this->getSession('order_email');
			if($order_email){
				$order = $this->m_order->Where(array('orderid'=>$orderid,'email'=>$order_email))->Where(array('isdelete'=>0))->SelectOne();
				if(!empty($order)){
					$data['order'] = $order;
					$data['cnstatus'] = array(0=>'<span class="layui-badge layui-bg-gray">待付款</span>',1=>'<span class="layui-badge layui-bg-blue">待处理</span>',2=>'<span class="layui-badge layui-bg-green">已完成</span>',3=>'<span class="layui-badge layui-bg-black">处理失败</span>');
				}
			}
			$data['querymethod'] = 'get';
		}else{
			$data['order'] =array();
			$data['querymethod'] = 'ajax';
		}
		$data['title'] = "订单查询";
        $this->getView()->assign($data);
    }
	
	public function ajaxAction()
	{
		$email    = $this->getPost('email',false);
		$chapwd    = $this->getPost('chapwd',false);
		$vercode = $this->getPost('vercode',false);
		$csrf_token = $this->getPost('csrf_token', false);
		
		if($email AND $chapwd AND $csrf_token AND $vercode){
			if ($this->VerifyCsrfToken($csrf_token)) {
				if(isEmail($email)){
					if(strtolower($this->getSession('productqueryCaptcha')) ==strtolower($vercode)){
						$this->unsetSession('productqueryCaptcha');
						$order = $this->m_order->Where(array('email'=>$email,'chapwd'=>$chapwd))->Where(array('isdelete'=>0))->Select();
						if(empty($order)){
							$data=array('code'=>1005,'msg'=>'订单不存在');
						}else{
							$data=array('code'=>1,'msg'=>'查询成功','data'=>$order,'count'=>1);
						}
					}else{
						$data=array('code'=>1004,'msg'=>'图形验证码错误');
					}
				}else{
					 $data = array('code' => 1003, 'msg' => '邮箱账户有误!');
				}
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}
	
	public function kamiAction()
	{
		$orderid    = $this->getPost('orderid',false);
		$csrf_token = $this->getPost('csrf_token', false);
		if($orderid AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$order = $this->m_order->Where(array('orderid'=>$orderid,'status'=>2))->SelectOne();
				if(empty($order)){
					$data=array('code'=>1005,'msg'=>'没有订单');
				}else{
					$card_mi_str = $order['kami'];
					$cards = explode(',',$card_mi_str);
					$data=array('code'=>1,'msg'=>'查询成功','data'=>$cards);
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
		$oid    = $this->getPost('oid',false);
		$csrf_token = $this->getPost('csrf_token', false);
		if($oid AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$order = $this->m_order->Where(array('id'=>$oid,'isdelete'=>0))->SelectOne();
				if(empty($order)){
					$data=array('code'=>1002,'msg'=>'没有订单');
				}else{
					if($order['status']<1){
						$data = array('code' => 1003, 'msg' => '未支付');
					}else{
						$this->setSession('order_email',$order['email']);
						$data = array('code' => 1, 'msg' => 'success','data'=>$order);
					}
				}
			} else {
				$data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}
}