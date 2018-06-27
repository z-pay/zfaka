<?php

/*
 * 功能：安装模块
 * Author:资料空白
 * Date:20180626
 */

class LastController extends BasicController
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