<?php

/*
 * 功能：管理设置
 * author:资料空白
 * time:20180508
 */

class SettingController extends PcBasicController
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