<?php

/*
 * 功能：会员中心－API
 * author:资料空白
 * time:20180509
 */

class ApiController extends PcBasicController
{
	private $m_api;
    public function init()
    {
        parent::init();
		$this->m_api = $this->load('api');
    }

    public function indexAction()
    {
        if ($this->login==FALSE AND !$this->userid) {
            $this->redirect("/member/login");
            return FALSE;
        }
		if($this->uinfo['isagent']>0){
			$data = array();
			$api = $this->m_api->Where(array('userid'=>$this->userid))->SelectOne();
			$data['api'] = $api;
			$this->getView()->assign($data);
		}else{
            $this->redirect("/member/");
            return FALSE;
		}
    }


    public function docAction()
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
	
	public function ajaxAction()
	{
        if ($this->login==FALSE AND !$this->userid) {
            $data = array('code' => 1000, 'msg' => '请登录');
			Helper::response($data);
        }
		
		if($this->uinfo['isagent']<1){
            $data = array('code' => 1000, 'msg' => '无权限');
			Helper::response($data);
		}
		$method_array = array('on','off');
		$method = $this->getPost('method',false);
		$csrf_token = $this->getPost('csrf_token', false);
		
		if($method AND $csrf_token){
			if ($this->VerifyCsrfToken($csrf_token)) {
				if(in_array($method,$method_array)){
					//检查是否已经使用api
					$checkApi = $this->m_api->Where(array('userid'=>$this->userid,'status'=>1))->SelectOne();
					if($method=='on'){
						if(empty($checkApi)){
							$apikey=date("YmdHis").str_pad($this->userid,3,"1", STR_PAD_LEFT);
							$apisecret=md5($apikey.$this->uinfo['email']).rand(10,90);
							
							$m = array(
								'userid'=>$this->userid,
								'apikey'=>$apikey,
								'apisecret'=>$apisecret,
								'allowip'=>'',
								'addtime'=>time(),
								'expirytime'=>strtotime("+1 year"),
								'status'=>1,
							);
							$newApi = $this->m_api->Insert($m);
							if($newApi){
								$data = array('code' => 1, 'msg' =>'success');
							}else{
								$data = array('code' => 1002, 'msg' =>'申请失败');
							}
						}else{
							$data=array('code'=>1004,'msg'=>'API已经存在');
						}
					}else{
						if(empty($checkApi)){
							$data=array('code'=>1004,'msg'=>'API不存在');
						}else{
							$newApi = $this->m_api->UpdateByID(array('status'=>0),$checkApi['id']);
							if($newApi){
								$data = array('code' => 1, 'msg' =>'success');
							}else{
								$data = array('code' => 1002, 'msg' =>'暂停失败');
							}
						}	
					}
				}else{
					 $data = array('code' => 1003, 'msg' => '方法错误!');
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