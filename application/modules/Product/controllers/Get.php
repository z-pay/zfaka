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

    public function bytypeidAction()
    {
		$typeid = $this->get('typeid');
		$data = array();
		$products = $this->m_products->Where(array('typeid'=>$typeid))->Select();
		$data['products'] = $products;
		$result = array('code' => 1, 'msg' => 'success','data'=>$data);
        Helper::response($result);
    }
}