<?php

/*
 * 功能：会员中心－首页
 * author:资料空白
 * time:20180509
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
		
		if($this->uinfo['isagent']>0){
            $this->redirect("/agent/");
            return FALSE;
		}else{
			$data = array();
			$this->getView()->assign($data);
		}
    }


}