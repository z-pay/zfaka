<?php

/*
 * 功能：后台中心－邮箱设置
 * Author:资料空白
 * Date:20180509
 */

class EmailController extends AdminBasicController
{
    private $m_email;
	
	public function init()
    {
        parent::init();
		$this->m_email = $this->load('email');
    }

    public function indexAction()
    {
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $this->redirect("/admin/login");
            return FALSE;
        }
		$data = array();
		$email = $this->m_email->SelectOne();
		$data['email'] = $email;
        $this->getView()->assign($data);
    }

	public function ajaxAction()
	{
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

}