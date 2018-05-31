<?php

/*
 * 功能：用户管理-代理商专用
 * author:资料空白
 * time:20180508
 */

class UserController extends PcBasicController
{
	private $m_user;
	
    public function init()
    {
        parent::init();
		$this->m_user = $this->load('user');
    }

    public function indexAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		
		if($this->uinfo['isagent']>0){
			$data = array();
			$this->getView()->assign($data);
		}else{
            $this->redirect("/member/");
            return FALSE;
		}

    }
	
	
	public function ajaxAction(){
        if ($this->login==FALSE AND !$this->userid) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		if($this->uinfo['isagent']<1){
            $data = array('code' => 1000, 'msg' => '无权限');
			Helper::response($data);
		}
		
		$where = array('agentid'=>$this->userid);
		
		$page = $this->get('page');
		$page = is_numeric($page) ? $page : 1;
		
		$limit = $this->get('limit');
		$limit = is_numeric($limit) ? $limit : 10;
		
		$total=$this->m_user->Where($where)->Total();
		
        if ($total > 0) {
            if ($page > 0 && $page < (ceil($total / $limit) + 1)) {
                $pagenum = ($page - 1) * $limit;
            } else {
                $pagenum = 0;
            }
			
            $limits = "{$pagenum},{$limit}";
			$items=$this->m_user->Where($where)->Limit($limits)->Order(array('id'=>'DESC'))->Select();
			
            if (empty($items)) {
                $data = array('code'=>0,'count'=>0,'data'=>array(),'msg'=>'无数据');
            } else {
                $data = array('code'=>0,'count'=>$total,'data'=>$items,'msg'=>'有数据');
            }
        } else {
            $data = array('code'=>0,'count'=>0,'data'=>array(),'msg'=>'无数据');
        }
		Helper::response($data);
	}
	
	
    public function addAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		
		if($this->uinfo['isagent']>0){
			$data = array();
			$this->getView()->assign($data);
		}else{
            $this->redirect("/member/");
            return FALSE;
		}

    }
	
}