<?php
/*
 * 功能：会员首页
 * Author:资料空白
 * Date:20180604
 */
class IndexController extends BasicController
{

	public function init()
	{
        parent::init();
	}

	public function indexAction()
	{
		if(file_exists(INSTALL_LOCK)){
			$version = @file_get_contents(INSTALL_LOCK);
			if(version_compare( $version, VERSION, '>=' )){
				$this->redirect("/install/upgrade");
				return FALSE;
			}else{
				$this->redirect("/product/");
				return FALSE;
			}
		}else{
			$this->redirect("/install/");
			return FALSE;
		}
	}
}