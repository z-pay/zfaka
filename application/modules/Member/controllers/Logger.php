<?php

/*
 * 功能：会员中心－日志中心
 * author:资料空白
 * time:20150902
 */

class LoggerController extends PcBasicController
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

}