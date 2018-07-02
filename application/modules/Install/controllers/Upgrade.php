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
		$desc = @file_get_contents(INSTALL_PATH.'/'.VERSION.'.text');
		$data['upgrade_desc'] = $desc;
		if(file_exists(INSTALL_PATH.'/'.VERSION.'.sql')){
			$data['upgrade_sql'] = INSTALL_PATH.'/'.VERSION.'.sql';
		}else{
			$data['upgrade_sql'] = '';
		}
		$this->getView()->assign($data);
    }
	

}