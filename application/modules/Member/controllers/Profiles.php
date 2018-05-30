<?php

/*
 * 功能：会员中心－个人中心
 * author:资料空白
 * time:20180509
 */

class ProfilesController extends PcBasicController
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
		$data = array();
		$uinfo = $this->m_user->SelectByID('nickname,email,qq,tag,createtime',$this->userid);
		$data['uinfo'] = $this->uinfo = array_merge($this->uinfo, $uinfo);
        $this->getView()->assign($data);
    }

	public function profilesajaxAction(){
		$nickname = $this->getPost('nickname',false);
		$qq = $this->getPost('qq',false);
		$tag = $this->getPost('tag',false);
		$csrf_token = $this->getPost('csrf_token', false);
		
		$data = array();
		
        if ($this->login==FALSE AND !$this->userid) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		if($nickname AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$this->m_user->UpdateByID(array('nickname'=>$nickname,'qq'=>$qq,'tag'=>$tag),$this->userid);
				$data = array('code' => 1, 'msg' => '更新成功');
			} else {
                $data = array('code' => 1001, 'msg' => '页面超时，请刷新页面后重试!');
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}
	
	
	
	public function passwordAction(){
		$data = array();
        $this->getView()->assign($data);
	}
	
	public function thirdloginAction(){
		$data = array();
        $this->getView()->assign($data);
	}
}