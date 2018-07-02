<?php

/*
 * 功能：后台中心－首页
 * Author:资料空白
 * Date:20180509
 */

class IndexController extends AdminBasicController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		if(file_exists(INSTALL_LOCK)){
			if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
				$this->redirect("/admin/login");
				return FALSE;
			}else{
				$version = @file_get_contents(INSTALL_LOCK);
				if(version_compare( VERSION, $version, '>=' )){
					$this->redirect("/install/upgrade");
					return FALSE;
				}else{
					$data = array();
					$this->getView()->assign($data);
				}
			}
		}else{
			$this->redirect("/install/");
			return FALSE;
		}
    }


}