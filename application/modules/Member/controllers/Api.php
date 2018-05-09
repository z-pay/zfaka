<?php

/*
 * 功能：会员中心－API
 * author:资料空白
 * time:20180509
 */

class ApiController extends PcBasicController
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


}