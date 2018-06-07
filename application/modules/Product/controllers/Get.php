<?php

/*
 * 功能：会员中心－个人中心
 * author:资料空白
 * time:20180509
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
		$data = array();
		$products = $this->m_products->Where(array('typeid'=>$tid,'ishidden'=>0))->Select();
		$data['products'] = $products;
		$result = array('code' => 1, 'msg' => 'success','data'=>$data);
        Helper::response($result);
    }
	
	
	public function proudctinfoAction()
	{
		$pid = $this->getPost('pid');
		$data = array();
		$product = $this->m_products->Where(array('id'=>$pid))->SelectOne();
		$data['product'] = $product;
		$result = array('code' => 1, 'msg' => 'success','data'=>$data);
        Helper::response($result);
	}
}