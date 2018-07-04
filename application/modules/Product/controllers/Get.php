<?php

/*
 * 功能：会员中心－个人中心
 * Author:资料空白
 * Date:20180509
 */

class GetController extends PcBasicController
{
	private $m_products;
    public function init()
    {
        parent::init();
		$this->m_products = $this->load('products');
    }

    public function proudctlistAction()
    {
		$tid = $this->getPost('tid');
		$csrf_token = $this->getPost('csrf_token', false);
		
		if($tid AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$data = array();
				$order = array('sort_num' => 'ASC');
				$field = array('id', 'name');
				$products = $this->m_products->Field($field)->Where(array('typeid'=>$tid,'active'=>1,'isdelete'=>0))->Order($order)->Select();
				$data['products'] = $products;
				$result = array('code' => 1, 'msg' => 'success','data'=>$data);
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$result = array('code' => 1000, 'msg' => '参数错误');
		}
        Helper::response($result);
    }
	
	
	public function proudctinfoAction()
	{
		$pid = $this->getPost('pid');
		$csrf_token = $this->getPost('csrf_token', false);
		if($pid AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$data = array();
				$field = array('id', 'name', 'price','auto', 'qty', 'stockcontrol', 'description');
				$product = $this->m_products->Field($field)->Where(array('id'=>$pid))->SelectOne();
				$data['product'] = $product;
				$result = array('code' => 1, 'msg' => 'success','data'=>$data);
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$result = array('code' => 1000, 'msg' => '参数错误');
		}
        Helper::response($result);
	}
}