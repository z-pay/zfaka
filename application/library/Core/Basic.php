<?php
/**
 * File: C_Basic.php
 * Functionality: Basic Controller
 * Author: 资料空白
 * Date: 2016-3-8
 */

class BasicController extends Yaf\Controller_Abstract {

	protected $uinfo=array();
	protected $userid=0;
	protected $login=FALSE;
	protected $config=array();
	protected $Pagination=NULL;
	protected $isHttps=FALSE;
	protected $isAjax=FALSE;
	protected $isGet=FALSE;
	protected $isPost=FALSE;
	
  	public function init(){	
		//初始化分页
		$this->Pagination=new Pagination();
		//系统默认配置
		$data['config']=$this->config=$this->load('config')->getConfig();
        $this->getView()->assign($data);
	}
	
	public function get($key, $filter = TRUE){
		if($filter){
			return filterStr($this->getRequest()->get($key));
		}else{
			return $this->getRequest()->get($key);
		}
	}

	public function getPost($key, $filter = TRUE){
		if($filter){
			return filterStr($this->getRequest()->getPost($key));
		}else{
			return $this->getRequest()->getPost($key);
		}
	}

	public function getParam($key, $filter = TRUE){
		if($filter){
			return filterStr($this->getRequest()->getParam($key));
		}else{
			return $this->getRequest()->getParam($key);
		}
	}
	
	public function getQuery($key, $filter = TRUE){
		if($filter){
			return filterStr($this->getRequest()->getQuery($key));
		}else{
			return $this->getRequest()->getQuery($key);
		}
	}

	public function getSession($key){
		return Yaf\Session::getInstance()->__get($key);
	}

	public function setSession($key, $val){
		return Yaf\Session::getInstance()->__set($key, $val);
	}

	public function unsetSession($key){
		return Yaf\Session::getInstance()->__unset($key);
	}


	public function clearCookie($key){
		$this->setCookie($key, '');
	}

	public function setCookie($key, $value, $expire = 3600, $path = '/', $domain = ''){
		setCookie($key, $value, CUR_TIMESTAMP + $expire, $path, $domain);
	}

	public function getCookie($key){
		return trim($_COOKIE[$key]);
	}

	public function load($model){
		return Helper::load($model);
	}
	
	public function show_message($code='',$msg='',$url='/'){
		return FALSE; 
	}

    public function setPage($total, $limit, $curPage, $baseUrl, $get_params)
    {
        $curP = 1;
        if((int)$curPage) {
            $curP = $curPage;
        }
        $config = [
            'base_url' => $baseUrl,
            'total_rows' => $total,
            'per_page' => $limit,
            'get_post_params' => $get_params,
            'cur_page' => $curP,
            'display_total_page' => TRUE
        ];
        $this->Pagination->initialize($config);
        $data['pagemenu'] = $this->Pagination->create_links();
        $pagenum = ($curPage > 0 && $curPage < (ceil($total / $limit) + 1)) ? ($curPage - 1) * $limit : 0;
        $limits = "{$pagenum},{$limit}";
        return array('pagemenu' => $data['pagemenu'], 'limit' => $limits, 'pagenum'=>$pagenum);
    }

}