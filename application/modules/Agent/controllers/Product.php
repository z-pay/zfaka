<?php

/*
 * 功能：代理商包含名下用户的产品-代理商专用
 * author:资料空白
 * time:20180508
 */

class ProductController extends PcBasicController
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
			$data = array();
			$this->getView()->assign($data);
		}else{
            $this->redirect("/member/");
            return FALSE;
		}

    }
	

}