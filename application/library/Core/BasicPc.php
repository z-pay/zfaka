<?php
/**
 * File: C_Basic.php
 * Functionality: Basic Controller
 * Author: 资料空白
 * Date: 2016-3-8
 */

class PcBasicController extends BasicController {
	protected $weixin=NULL;
	protected $isWeixin=FALSE;
	public $serverPrivateKey = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKrU5gne1HvK18yk9aFX+LIgf8bIZvW/TgAAQWUkLkVDf1s91r6JmlmJsvGDz1KWuFEtU5k+ZTY+znh0ncLfgdTcmVvymp1D4fhEKt/JSaZNZe7Fb3kfl7iT15pQBivirrkpP1dwyM5EzafkRo5wKOktbQLYglW/e+ChVf4L+mqXAgMBAAECgYBcweb6Wwzi/rv4OWXKKps2FSFsTSpiq3Jt27WmdmPNZh4D6+rrYIn3riYEr35mKMKCCWuIHPIV5zpy+1ciFfxHNifvwVs9zpWGYkuvyI2Ar41zODI8doYFaQjWUBf/xJziabTEn1pFsH+Q8xWqr0fXdFdKYt6lYnjZR3bJIL79yQJBANaEQ0MqPqbj4s6L++igcgizkPOQ00a0kRdv6R0wQWqXg5fseg776sUv301XYbTnc7BlmHsQUQsYcROOqzhZlNsCQQDL3f2ehMGecX2qnImBGbXIRIIF1DnjULDzBpz/ijMYg1trIRRjBirWFj6cQOEOxlW2A8qpz1ZxR9zfSzjYXG/1AkBPn8xvs9CJlfDsBd29XUC2piBZqBokFoX8kxeONAk0DYVU8Pvlb/CWvMxAIv0rbvXsNenBVC8g1TOztLMtOWMdAkEAgC1ZyXHknm7yuPNkzOPSVFEmgu21W8OfDZ2p1k0Y5R+puch5ne0Bv8sKoIl2NyjiOOdXY761tdGeAFK2MeqkhQJALGjfBtrV9c3u3XVVbpASadkkOcUvXOb8fyRvTv03Bg3cbF3hP6ucb5SPEg6dDHixRj25S+JTiYH5WxbtyYni5g==";
  	public function init(){	
		parent::init();
		//系统默认
		$sysvars=array();
		$sysvars['isHttps']=$this->isHttps=isHttps();
		$sysvars['isAjax']=$this->isAjax=isAjax();
		$sysvars['isGet']=$this->isGet=isGet();
		$sysvars['isPost']=$this->isPost=isPost();
		$sysvars['currentUrl']=stripHTML(str_replace('//', '/',$_SERVER['REQUEST_URI']));
		$sysvars['currentUrlSign']=md5(URL_KEY.$sysvars['currentUrl']);
		$data['sysvars']=$sysvars; 
		//2.获取当前路径页面的SEO设置
		$request = \Yaf\Dispatcher::getInstance()->getRequest();
		$seo_array=$this->load('seo')->getConfig();
		$seo_k=$request->getModuleName().'-'.$request->getControllerName().'-'.$request->getActionName();
		if(isset($seo_array[$seo_k]) AND !empty($seo_array[$seo_k])){
			$seo=$seo_array[$seo_k];
			$data['title']=$seo['title']?$seo['title']:'';
			$data['keywords']=$seo['keywords']?$seo['keywords']:'';
			$data['description']=$seo['description']?$seo['description']:'';
		}else{
			$data['title']='';
			$data['keywords']='';
			$data['description']='';
		}
		//3.登录的判断,加入登录超时判断
        $uinfo = $this->getSession('uinfo');
		if(is_array($uinfo) AND !empty($uinfo) AND $uinfo['expiretime']>time()){
			// 4.单点登录判断
			$websession=$this->load('user')->SelectByID('web_session',$uinfo['id']);
			if(is_array($websession) AND ($websession['web_session']!=session_id())){
				$data['login']=$this->login=false;
				$this->unsetSession('uinfo');
			}else{
				$uinfo['expiretime']= time() + 15*60;
				$this->setSession('uinfo',$uinfo);
				$data['login']=$this->login=true;
				$data['uinfo']= $this->uinfo=$uinfo;
				$this->userid=$uinfo['id'];
			}
		}else{
			$data['login']=$this->login=false;
			$this->unsetSession('uinfo');
		}
		//微信打开的
  		if(isMobile() AND isWeixin()){
			$this->isWeixin=TRUE;
			$this->weixin = WeixinApi::getInstance();
		}
		$data['isWeixin']=$this->isWeixin;
		//防csrf攻击
		$data['csrf_token'] = $this->createCsrfToken();
        $this->getView()->assign($data);
	}

    //生成JWT token
    public function createToken()
    {
        $tokenKey = array(
            "iss" => "https://www.mtrp2p.com",  //jwt签发者
            "aud" => 'RPC',                     //接收jwt的一方
            "exp" => time() + 60,               //过期时间
        );
        return JWT::encode($tokenKey, self::readRSAKey($this->serverPrivateKey), 'RS256');
    }
    //为JWT准备的，证书处理函数
    private static function readRSAKey($key)
    {
        $isPrivate = strlen($key) > 500;
        if ($isPrivate) {
            $lastKey = chunk_split($key, 64, "\n");
            $lastKey = "-----BEGIN RSA PRIVATE KEY-----\n" . $lastKey . "-----END RSA PRIVATE KEY-----\n";
            return $lastKey;
        } else {
            $lastKey = chunk_split($key, 64, "\n");
            $lastKey = "-----BEGIN PUBLIC KEY-----\n" . $lastKey . "-----END PUBLIC KEY-----\n";
            return $lastKey;
        }
    }	
	public function show_message($code='',$msg='',$url='/'){
		$this->forward("Index",'Showmsg','index',array('code'=>$code,'msg'=>$msg,'url'=>$url));
		return FALSE; 
	}
	
	//生成csrftoken　防csrf攻击
    private function createCsrfToken(){
    	$csrf_token=$this->getSession('csrf_token');
    	if(!$csrf_token){
    		$csrf_token=$this->createToken(); 
			$this->setSession('csrf_token',$csrf_token);
    	}
		return $csrf_token;
	}
	//验证csrftoken 防csrf攻击
	public function VerifyCsrfToken($csrf_token=''){
		$csrf_token=$csrf_token?$csrf_token:$this->getPost('csrf_token',false);
		$session_csrf_token=$this->getSession('csrf_token'); 
		if($session_csrf_token && $session_csrf_token==$csrf_token){
			if(!isAjax()){
				$this->setSession('csrf_token','');
			}
			return true;
		}else{
			return false;
		}
	}
}