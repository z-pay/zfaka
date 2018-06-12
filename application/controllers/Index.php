<?php
/*
 * 功能：会员首页
 * Author:资料空白
 * Date:20180604
 */
class IndexController extends PcBasicController
{

	public function init()
	{
        parent::init();
	}

	public function indexAction()
	{
		if(!$this->login OR empty($this->uinfo))
		{
			$this->redirect("/product/");
		}else{
			$this->redirect("/member/");
		}
		return FALSE;
	}
}