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
            $this->redirect('/'.ADMIN_DIR."/login");
            return FALSE;
        }
		$data = array();
		$email = $this->m_email->SelectOne();
		$data['email'] = $email;
        $this->getView()->assign($data);
    }

	public function ajaxAction()
	{
		$method = $this->getPost('method',false);
		$id = $this->getPost('id',false);
		$mailaddress = $this->getPost('mailaddress',false);
		$mailpassword = $this->getPost('mailpassword',false);
		$sendmail = $this->getPost('sendmail',false);
		$sendname = $this->getPost('sendname',false);
		$host = $this->getPost('host',false);
		$port = $this->getPost('port',false);
		$isssl = $this->getPost('isssl');
		$csrf_token = $this->getPost('csrf_token', false);
		
		$data = array();
		
        if ($this->AdminUser==FALSE AND empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		if($method AND $mailaddress AND $mailpassword AND $sendmail AND $sendname AND $host AND $port AND is_numeric($isssl) AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				$m = array(
					'mailaddress'=>$mailaddress,
					'mailpassword'=>$mailpassword,
					'sendmail'=>$sendmail,
					'sendname'=>$sendname,
					'host'=>$host,
					'port'=>$port,
					'isssl'=>$isssl
				);
				if($method == 'edit' AND $id>0){
					$u = $this->m_email->UpdateByID($m,$id);
					if($u){
						//更新缓存 
						$this->m_email->getConfig(1);
						$data = array('code' => 1, 'msg' => '更新成功');
					}else{
						$data = array('code' => 1003, 'msg' => '更新失败');
					}
				}else{
					$id = $this->m_email->Insert($m);
					if($id>0){
						//更新缓存 
						$this->m_email->getConfig(1);
						$data = array('code' => 1, 'msg' => '新增成功');
					}else{
						$data = array('code' => 1003, 'msg' => '新增失败');
					}
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