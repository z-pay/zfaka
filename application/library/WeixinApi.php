<?php
class WeixinApi{
	private static $_instance;
	private $weixinAccount;
	private function __construct(){
		$this->weixinAccount=Helper::load('weixin_account')->getConfig();
	}

	public function __clone(){
		trigger_error('Clone is not allow!',E_USER_ERROR);
	}

	public static function getInstance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self;
		}
		return self::$_instance;
	}

	/**
	 * 接口验证-主域名和回调域名
	 * @param unknown_type $_GET
	 * @param bool
	 */
	public function is_token(){
		$echoStr = $_GET["echostr"];
		$wid=$_GET["wid"];
		if($this->checkSignature($wid)){
			echo $echoStr;
			exit;
		}
	}
	//检测token
	private function checkSignature($wid=1) {
		if(isset($this->weixinAccount[$wid])){
			$token=$this->weixinAccount[$wid]['token'];
			$signature = $_GET["signature"];
			$timestamp = $_GET["timestamp"];
			$nonce = $_GET["nonce"];
			$tmpArr = array($token, $timestamp, $nonce);
			sort($tmpArr,SORT_STRING);
			$tmpStr = implode($tmpArr);
			$tmpStr = sha1($tmpStr);
			if($tmpStr == $signature){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	/**
	 * 创建菜单 这里是本公众号的主域名和回调域名
	 * @param  openid,appid,secret
	 * @param  用户信息
	 */
	public function creatMenu($data,$wid=1){
		if(isset($this->weixinAccount[$wid])){
			$appid=$this->weixinAccount[$wid]['appid'];
			$secret=$this->weixinAccount[$wid]['secret'];
			$url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->getAccessToken($appid,$secret);
			$result = json_decode($this->https_request($url,$data));
			return $result;
		}else{
			return array();
		}
	}

    //发送消息给用户
    public function sendMsg($openid,$msg,$wid=1){
		if(isset($this->weixinAccount[$wid])){
			$appid=$this->weixinAccount[$wid]['appid'];
			$secret=$this->weixinAccount[$wid]['secret'];
			$accesstoken=$this->getAccessToken($appid,$secret);
			if($accesstoken){
				$url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$accesstoken}";
				$jsonData='{"touser":"'.$openid.'","msgtype":"text","text":{"content":"'.$msg.'"}}';
				$re =json_decode($this->JsonPost($url, $jsonData),true);
				if(is_array($re) AND isset($re['errcode']) AND (int)$re['errcode']==0){
					return array('code'=>1,'msg'=>$re['errmsg']);
				}else{
					$msg=isset($re['errmsg'])?$re['errmsg']:'失败';
					return array('code'=>0,'msg'=>$msg);
				}
			}else{
				return array('code'=>0,'msg'=>"获取accesstoken失败");
			}
		}else{
			return array('code'=>0,'msg'=>"不存在的公众号ID");
		}
    }

	//发送通知模板消息-参数
	public function sendTemplateMsg($params,$wid=1){
		if(isset($this->weixinAccount[$wid])){
			$appid=$this->weixinAccount[$wid]['appid'];
			$secret=$this->weixinAccount[$wid]['secret'];
			$data=array();
			foreach($params['data'] AS $k=>$i){
				$data[$k]['value']=$i;
				$data[$k]['color']="#173177";
			}
			//处理地址链接，如果不存在就用默认
			if(isset($params['url']) AND $params['url']!=''){
				$click_url=$params['url'];
			}else{
				$click_url='';
			}
			$items=array(
				'touser'=>$params['touser'],
				'template_id'=>$params['template_id'],
				'url'=>$click_url,
				'topcolor'=>'#FF0000',
				'data'=>$data,
			);
			$json=json_encode($items);
			$accesstoken=$this->getAccessToken($appid,$secret);
			if($accesstoken){
				$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$accesstoken;
				$re =  json_decode($this->JsonPost($url, $json),true);
				if(isset($re['errcode']) AND (int)$re['errcode']==0){
					return array('code'=>1,'msg'=>$re['errmsg']);
				}else{
					return array('code'=>0,'msg'=>$re['errmsg']);
				}
			}else{
				return array('code'=>0,'msg'=>"获取accesstoken失败");
			}
		}else{
			return array('code'=>0,'msg'=>"不存在的公众号ID");
		}
	}

	/**
	 * 第一步：用户同意授权，获取code 网页授权 这里是本公众号的主域名和回调域名 获取用户openid
	 * @param unknown_type $appid
	 * @param $uri 跳转地址
	 */
	public function wxLogin_scope($uri,$wid=1){
		if(isset($this->weixinAccount[$wid])){
			$appid=$this->weixinAccount[$wid]['appid'];
			$url=array('appid'=>$appid,
				'redirect_uri'=>$uri,
				'response_type'=>'code',
				'scope'=>'snsapi_base',
				'state'=>'1'
			);
			header('location:https://open.weixin.qq.com/connect/oauth2/authorize?'.http_build_query($url).'#wechat_redirect');
			exit();
		}else{
			exit('error');
		}
	}
	/**
	 * 第一步：用户同意授权，获取code 网页授权 网页授权 这里是本公众号的主域名和回调域名 获取用户基本信息
	 * @param unknown_type $appid
	 * @param $uri 跳转地址
	 */
	public function wxLogin_User($uri,$wid=1){
		if(isset($this->weixinAccount[$wid])){
			$appid=$this->weixinAccount[$wid]['appid'];
			$url=array('appid'=>$appid,
				'redirect_uri'=>$uri,
				'response_type'=>'code',
				'scope'=>'snsapi_userinfo',
				'state'=>'1'
			);
			header('location:https://open.weixin.qq.com/connect/oauth2/authorize?'.http_build_query($url).'#wechat_redirect');
			exit();
		}else{
			exit('error');
		}
	}

	/**
	 * 第二步：通过code换取网页授权access_token 获取openid
	 */
	public function getAuthorizes($wid=1){
		$result=array();
		if(isset($this->weixinAccount[$wid])){
			$appid=$this->weixinAccount[$wid]['appid'];
			$secret=$this->weixinAccount[$wid]['secret'];
			$code = $_GET['code'];
			$state = $_GET['state'];
			if ($code){
				//获取token
				$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
				$token = json_decode(file_get_contents($token_url));
				if (isset($token->errcode)) {
					/*
					echo '<h1>错误1：</h1>'.$token->errcode;
					echo '<br/><h2>错误信息：</h2>'.$token->errmsg;
					exit;*/
				}else{
					$result['openid']=$token->openid;
					$result['unionid']=$token->unionid;
					$result['access_token']=$token->access_token;
					$result['appid']=$appid;
				}
			}
		}
		return $result;
	}

	/**第四步：拉取用户信息(需scope为 snsapi_userinfo)
	 * 获取手机端网页登录授权
	 */
	public function getAuthorizesUser($wid){
		$result=array();
		if(isset($this->weixinAccount[$wid])){
			$appid=$this->weixinAccount[$wid]['appid'];
			$secret=$this->weixinAccount[$wid]['secret'];
			$code = $_GET['code'];
			$state = $_GET['state'];
			if ($code){
				//获取token
				$token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
				$token = json_decode(file_get_contents($token_url));
				if (isset($token->errcode)) {
					/*echo '<h1>错误1：</h1>'.$token->errcode;
					echo '<br/><h2>错误信息：</h2>'.$token->errmsg;
					exit;*/
				}else{
					$access_token=$token->access_token;
					$openid=$token->openid;
					if($access_token AND $openid){
						//拉去信息
						$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
						$user_info = json_decode(file_get_contents($user_info_url),true);
						if (isset($user_info['errcode'])) {
							/*echo '<h1>错误2：</h1>'.$user_info['errcode'];
							echo '<br/><h2>错误信息：</h2>'.$user_info['errmsg'];
							exit;*/
						}else{
							$result=$user_info;
						}
					}
				}
			}
		}
		return $result;
	}
	/**
	 * 获取用户基本信息（包括UnionID机制） 已关注用户才能获取此信息
	 * @param  openid,appid,secret
	 * @param  用户信息
	 */
	public function getWeixinUser($openid,$wid=1,$new=0){
		$result=array();
		if(isset($this->weixinAccount[$wid]) AND $openid){
			$appid=$this->weixinAccount[$wid]['appid'];
			$secret=$this->weixinAccount[$wid]['secret'];
			$accesstoken=$this->getAccessToken($appid, $secret,$new);
			if($accesstoken){
				$api="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$accesstoken}&openid={$openid}&lang=zh_CN";
				$result=json_decode(file_get_contents($api),true);
				if (isset($result['errcode'])) {
					if($result['errcode']=='40001'){
						//这里是因为token过期了
						return $this->getWeixinUser($openid,$wid,1);
					}
				}
			}
		}
		return $result;
	}

	/**
	 * JS安全域名操作 支持多公众号
	 * @param  openid,appid,secret
	 * @param  用户信息
	 */
    public function getSignPackage($wid=1) {
		$result=array();
		if(isset($this->weixinAccount[$wid])){
			$appid=$this->weixinAccount[$wid]['appid'];
			$secret=$this->weixinAccount[$wid]['secret'];
			$jsapiTicket = $this->getJsApiTicket($appid,$secret);
			if(is_array($jsapiTicket) AND isset($jsapiTicket['code'])){
				return $result;
			}
			// 注意 URL 一定要动态获取，不能 hardcode.
			//$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$protocol = "https://" ;
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$timestamp = time();
			$nonceStr = $this->createNonceStr();
			// 这里参数的顺序要按照 key 值 ASCII 码升序排序
			$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
			$signature = sha1($string);
			$result = array(
				"appId"     => $appid,
				"nonceStr"  => $nonceStr,
				"timestamp" => $timestamp,
				"url"       => $url,
				"signature" => $signature,
				"rawString" => $string
			);
		}
    	return $result;
    }
    private function getJsApiTicket($appid,$secret) {
		$data = $config = array();
		$file_path=TEMP_PATH."/{$appid}-jsapi_ticket.json";
		if(file_exists($file_path)){
			$data = json_decode(file_get_contents($file_path),true);
		}
		//取旧值
		if(!empty($data) AND isset($data['config']) AND (isset($data['expire_time']) AND $data['expire_time'] < time()) ){
			$config =$data['config'];
		}else{
			$config =array();
		}

		if (empty($config)){
			$accessToken = $this->getAccessToken($appid, $secret);
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$accessToken}";
			$result = json_decode($this->httpGet($url));
    		if ($result->ticket) {
				$data =array();
				$data['expire_time'] = time() + 6500;
				$data['config'] = $config =$result->ticket;
				$fp = fopen($file_path, "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
    		}else{
				return array('code'=>$result->errcode,'msg'=>$result->errmsg);
			}
    	}
		return $config;
    }

    private function createNonceStr($length = 16) {
    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    	$str = "";
    	for ($i = 0; $i < $length; $i++) {
    		$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    	}
    	return $str;
    }

    // 四、创建二维码
	public function creatQrcode($id,$type=true,$wid=1){
		$result='';
		if(isset($this->weixinAccount[$wid])){
			$appid=$this->weixinAccount[$wid]['appid'];
			$secret=$this->weixinAccount[$wid]['secret'];
			$access_token=$this->getAccessToken($appid,$secret);
			if($access_token){
				if($type){
					$tempJson = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id":'.$id.'}}}';
				}else{
					$tempJson = '{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$id.'}}}';
				}
				$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
				$tempArrs = json_decode($this->JsonPost($url, $tempJson),true);
				if($tempArrs['errcode']){
					echo '获取二维码错误'.$tempArrs['errcode'];exit;
				}
				$result='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($tempArrs['ticket']);
			}
		}
		return $result;
    }

	/**
	 * 微信端接口获取token用 需要支持多域名多公众号
	 * @param  appid,secret
	 * @param  tokey
	 */
	private function getAccessToken($appid,$secret,$new=0){
		$data = $config = array();
		$file_path=TEMP_PATH ."/{$appid}-token.json";
		if(file_exists($file_path) AND !$new){
			$data = json_decode(file_get_contents($file_path),true);
		}
		//取旧值
		if(!empty($data) AND isset($data['config']) ){
			$config =$data['config'];
		}
		if (empty($config) OR (isset($data['expire_time']) AND $data['expire_time'] < time()) OR $new){
			$api="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
			$result=json_decode(file_get_contents($api));
    		if (isset($result->errcode)) {
    			/*echo '<h1>错误token：</h1>'.$result->errcode;
    			echo '<br/><h2>错误信息token：</h2>'.$result->errmsg;
    			exit;*/
    		}else{
				$data = array();
				$data['expire_time'] = time() + 6500;
				$data['config'] = $config =$result->access_token;
				$fp = fopen($file_path, "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
    	}
		return $config;
	}

	// CRULpost方法
    private  function JsonPost($url,$jsonData){
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
	    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $result = curl_exec($curl);
	    if (curl_errno($curl)) {
			$this->ErrorLogger('curl falied. Error Info: '.curl_error($curl));
	    }
	    curl_close($curl);
	    return $result;
    }

    // curl 兼容post get
	private function https_request($url,$data = null){
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
	    	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    }
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($curl);
	    curl_close($curl);
	    return $output;
	}

	// curl get方式
	private function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	}


}