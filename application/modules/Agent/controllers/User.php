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
	
	public function addajaxAction()
	{
        if ($this->login==FALSE AND !$this->userid) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		if($this->uinfo['isagent']<1){
            $data = array('code' => 1000, 'msg' => '无权限');
			Helper::response($data);
		}
		$email    = $this->getPost('email',false);
		$password = $this->getPost('password',false);
		$nickname = $this->getPost('nickname',false);
		$qq = $this->getPost('qq',false);
		$tag = $this->getPost('tag',false);
		$csrf_token = $this->getPost('csrf_token', false);
		
		if($email AND $password AND $nickname AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				if(isEmail($email)){
					//检查邮箱是否已经使用
					$checkEmailUser = $this->m_user->checkEmail($email);
					if(empty($checkEmailUser)){
						$m = array(
							'email'=>$email,
							'password'=>$password,
							'nickname'=>$nickname,
							'qq'=>$qq,
							'tag'=>$tag,
							'agentid'=>$this->userid,
							'method'=>'agentadd',
						);
						$newUser = $this->m_user->newRegister($m);
						if($newUser){
							$data = array('code' => 1, 'msg' =>'success');
						}else{
							$data = array('code' => 1002, 'msg' =>'注册失败');
						}
					}else{
						$data=array('code'=>1004,'msg'=>'邮箱账户已经存在');
					}
				}else{
					 $data = array('code' => 1003, 'msg' => '邮箱账户有误!');
				}
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}
	
}