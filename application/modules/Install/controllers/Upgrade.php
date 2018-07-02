<?php

/*
 * 功能：安装升级模块
 * Author:资料空白
 * Date:20180702
 */

class UpgradeController extends BasicController
{

	public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		/*if(file_exists(INSTALL_LOCK)){
			$this->redirect("/product/");
			return FALSE;
		}else{
			$data = array();
			$this->getView()->assign($data);
		}*/
		$data = array();
		$this->getView()->assign($data);
    }
	

}