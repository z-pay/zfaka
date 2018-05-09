<?php

/*
 * 功能：会员中心－个人中心
 * author:资料空白
 * time:20180509
 */

class ProfilesController extends PcBasicController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		$data = array();
        $this->getView()->assign($data);
    }


	public function headimgAction(){
		$data = array();
        $this->getView()->assign($data);
	}
	
	public function thirdloginAction(){
		$data = array();
        $this->getView()->assign($data);
	}
}