<?php

/*
 * 功能：后台中心－统计报表
 * Author:资料空白
 * Date:20180509
 */

class ReportController extends AdminBasicController
{
    private $m_order;
	
	public function init()
    {
        parent::init();
		$this->m_order = $this->load('order');
    }

    public function indexAction()
    {
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $this->redirect('/'.ADMIN_DIR."/login");
            return FALSE;
        }
		$data = array();
		$data['title'] = "统计报表";
        $this->getView()->assign($data);
    }


}