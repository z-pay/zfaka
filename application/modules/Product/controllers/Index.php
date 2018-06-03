<?php

/*
 * 功能：产品中心
 * author:资料空白
 * time:20180508
 */

class IndexController extends PcBasicController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$data = array();
        $this->getView()->assign($data);
    }
	
	//防火墙
    public function fwAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$data = array();
        $this->getView()->assign($data);
    }	
}