<?php

/*
 * 功能：会员中心－登录类
 * author:资料空白
 * time:20150902
 */

class LoginController extends PcBasicController
{
	private $m_user;
	
    public function init()
    {
        parent::init();
		$this->m_user = $this->load('user');
    }

    public function indexAction()
    {
        if (false != $this->login AND $this->userid) {
            $this->redirect("/member/");
            return FALSE;
        }
		
        //对refererUrl进行有效性校验
        $referer_url = $this->get('referer_url', false);
        $sign        = $this->get('sign', false);
		
        if (md5(URL_KEY . $referer_url) === $sign) {
            $data['referer_url'] = $referer_url;
        } else {
            $data['referer_url'] = '';
        }
        $data['cookie_email'] = $this->getCookie('email');
        $this->getView()->assign($data);
    }
	
	public function ajaxAction()
	{
		$email    = $this->getPost('email',false);
		$password = $this->getPost('password',false);
		if($email AND $password){
			$checkUser = $this->m_user->checkLogin($email,$password);
			if($checkUser){
				$this->redirect("/member/");
				return FALSE;
			}else{
				echo "登录失败";
				exit();
			}
		}else{
			echo "参数错误";
			exit();
		}
	}
}