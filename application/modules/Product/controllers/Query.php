<?php

/*
 * 功能：会员中心－个人中心
 * author:资料空白
 * time:20180509
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
						$order = $this->m_order->Where(array('email'=>$email,'chapwd'=>$chapwd))->Select();
						if(empty($order)){
							$data=array('code'=>1005,'msg'=>'没有订单');
						}else{
							$data=array('code'=>1,'msg'=>'查询成功','data'=>array('order'=>$order),'count'=>1);
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
}