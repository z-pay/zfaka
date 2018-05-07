<?php
/**
*类名：阿里大鱼短信类
*时间：20160219
*注意事项：为了方便变量使用，阿里大鱼模版设置中，请按照26个小写英文字母顺序设置
**/
namespace Alidayu;
class Alidayu{
	private $appkey;
	private $secret;
	private $CalledShowNum;
	private $singtext;
	
	function __construct(){
		\Yaf\Loader::import(LIB_PATH.'/Alidayu/TopSdk.php');
		$sms=\Helper::load('sms')->getConfig();
		$this->appkey=$sms['appkey'];
		$this->secret=$sms['secret'];
		$this->CalledShowNum=$sms['CalledShowNum'];
		$this->singtext=$sms['singtext'];
	}
	
	public function getAppkey(){
		return $this->appkey;
	}
	public function getSecret(){
		return $this->secret;
	}
	public function getCalledShowNum(){
		return $this->CalledShowNum;
	}
	public function getSingtext(){
		return $this->singtext;
	}	
	
	
	/**
	*函数名：短信发送
	*参数说明：
		phonenumber手机号,格式：字符串，群发短信需传入多个号码，以英文逗号分隔，一次调用最多传入200个号
		tpl模版ID，格式：字符串
		params变量，格式：数组
		uid变量，用户ID，默认可以不填
	*返回格式：数组 code 0失败，code1成功
	**/
	public function smsSend($phonenumber,$tpl,$params=array(),$uid=''){
		$result=array();
		$c = new \TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new \AlibabaAliqinFcSmsNumSendRequest;
		if($uid){
			$req->setExtend($uid);
		}
		$req->setSmsType("normal");
		$req->setSmsFreeSignName($this->singtext);
		
		if(is_array($params) AND !empty($params)){
			foreach($params AS $key=>$value){
				$params[$key]= (string)$value;
			}
			$param=json_encode($params);
			$req->setSmsParam($param);
		}
		
		$req->setRecNum($phonenumber);
		$req->setSmsTemplateCode($tpl);
		$resp=$c->execute($req);
		if(isset($resp->code)){
			$result=array('code'=>$resp->code,'message'=>$resp->msg,'sub_code'=>$resp->sub_code,'sub_msg'=>$resp->sub_msg);
		}else{
			$result=array('code'=>1,'message'=>$resp->result->model);
		}
		return $result;
	}
	
	public function voiceSend($phonenumber,$tpl,$params=array(),$uid=''){
		$result=array();
		//随机取一个显示号码
		$k=array_rand($this->CalledShowNum);
		$CalledShowNum=$this->CalledShowNum[$k];
		$c = new \TopClient;
		$c->appkey = $this->appkey;
		$c->secretKey = $this->secret;
		$req = new \AlibabaAliqinFcTtsNumSinglecallRequest;
		if($uid){
			$req->setExtend($uid);
		}
		if(is_array($params) AND !empty($params)){
			foreach($params AS $key=>$value){
				$params[$key]= (string)$value;
			}		
			$param=json_encode($params);
			$req->setTtsParam($param);
		}		
		
		$req->setCalledNum($phonenumber);
		$req->setCalledShowNum($CalledShowNum);
		$req->setTtsCode($tpl);
		$resp=$c->execute($req);
		if(isset($resp->code)){
			$result=array('code'=>$resp->code,'message'=>$resp->msg);
		}else{
			$result=array('code'=>1,'message'=>$resp->result->model);
		}
		return $result;
	}
}