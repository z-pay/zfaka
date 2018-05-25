<?php

/*
 * 功能：会员中心－注册类
 * author:资料空白
 * time:20150902
 */

class RegisterController extends PcBasicController
{

    private $m_user;

    public function init()
    {
        parent::init();
        $this->m_user = $this->load('user');
    }

    public function indexAction()
    {
        if (false != $this->login AND false != $this->userid) {
            $this->redirect("/member/center/");
            return FALSE;
        }
		$data = array();
        $this->getView()->assign($data);
    }

	public function ajaxAction()
	{
		$email    = $this->getPost('email',false);
		$password = $this->getPost('password',false);
		$nickname = $this->getPost('nickname',false);
		$vercode = $this->getPost('vercode',false);
		$csrf_token = $this->getPost('csrf_token', false);
		
		if($email AND $password AND $nickname AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$m = array('email'=>$email,'password'=>$password,'nickname'=>$nickname);
				$newUser = $this->m_user->newRegister($m);
				if($newUser){
					$data = array('code' => 1, 'msg' =>'success');
				}else{
					$data = array('code' => 1002, 'msg' =>'注册失败');
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