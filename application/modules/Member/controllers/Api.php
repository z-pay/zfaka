<?php

/*
 * 功能：会员中心－API
 * author:资料空白
 * time:20180509
 */

class ApiController extends PcBasicController
{
	private $m_api;
    public function init()
    {
        parent::init();
		$this->m_api = $this->load('api');
    }

    public function indexAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$data = array();
		$api = $this->m_api->Where(array('userid'=>$this->userid))->SelectOne();
		$data['api'] = $api;
        $this->getView()->assign($data);
    }


    public function docAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		$data = array();
        $this->getView()->assign($data);
    }	
}