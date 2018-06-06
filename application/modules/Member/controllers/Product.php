<?php

/*
 * 功能：会员中心－个人中心
 * author:资料空白
 * time:20180509
 */

class ProductController extends PcBasicController
{
	private $m_products_type;
    public function init()
    {
        parent::init();
		$this->m_products_type = $this->load('products_type')
    }

    public function indexAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$data = array();
		$products_type = $this->m_products_type->Select();
		$data['products_type'] = $products_type;
        $this->getView()->assign($data);
    }
}