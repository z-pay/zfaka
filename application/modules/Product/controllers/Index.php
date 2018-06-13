<?php

/*
 * 功能：会员中心－个人中心
 * Author:资料空白
 * Date:20180509
 */

class IndexController extends PcBasicController
{
	private $m_products_type;
    public function init()
    {
        parent::init();
		$this->m_products_type = $this->load('products_type');
    }

    public function indexAction()
    {
		$data = array();
		$order = array('sort_num' => 'ASC');
		$products_type = $this->m_products_type->Where(array('active'=>1))->Order($order)->Select();
		$data['products_type'] = $products_type;
        $this->getView()->assign($data);
    }
}