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
		$data = array();
        $this->getView()->assign($data);
    }
}