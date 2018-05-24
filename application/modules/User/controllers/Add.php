<?php

/*
 * 功能：添加用户
 * author:资料空白
 * time:20180508
 */

class AddController extends PcBasicController
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