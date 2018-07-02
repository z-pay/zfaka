<?php

/*
 * 功能：后台中心－邮件队列
 * Author:资料空白
 * Date:20180509
 */

class EmailqueueController extends AdminBasicController
{
	private $m_email_queue;
    public function init()
    {
        parent::init();
		$this->m_email_queue = $this->load('email_queue');
    }

    public function indexAction()
    {
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $this->redirect("/admin/login");
            return FALSE;
        }

		$data = array();
		$this->getView()->assign($data);
    }

	//ajax
	public function ajaxAction()
	{
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		$where = array();
		
		$page = $this->get('page');
		$page = is_numeric($page) ? $page : 1;
		
		$limit = $this->get('limit');
		$limit = is_numeric($limit) ? $limit : 10;
		
		$total=$this->m_email_queue->Where($where)->Total();
		
        if ($total > 0) {
            if ($page > 0 && $page < (ceil($total / $limit) + 1)) {
                $pagenum = ($page - 1) * $limit;
            } else {
                $pagenum = 0;
            }
			
            $limits = "{$pagenum},{$limit}";
			$items=$this->m_email_queue->Where($where)->Limit($limits)->Order(array('id'=>'DESC'))->Select();
			
            if (empty($items)) {
                $data = array('code'=>1001,'count'=>0,'data'=>array(),'msg'=>'无数据');
            } else {
                $data = array('code'=>0,'count'=>$total,'data'=>$items,'msg'=>'有数据');
            }
        } else {
            $data = array('code'=>1000,'count'=>0,'data'=>array(),'msg'=>'无数据');
        }
		Helper::response($data);
	}
}