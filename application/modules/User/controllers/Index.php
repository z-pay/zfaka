<?php

/*
 * 功能：用户管理
 * author:资料空白
 * time:20180508
 */

class IndexController extends PcBasicController
{
	private $m_user;
	
    public function init()
    {
        parent::init();
		$this->m_user = $this->load('user');
    }

    public function indexAction()
    {
		$data = array();
		$itmes = $this->m_user->Select();
		$data['itmes'] =$itmes;
		
        $this->getView()->assign($data);
    }
}