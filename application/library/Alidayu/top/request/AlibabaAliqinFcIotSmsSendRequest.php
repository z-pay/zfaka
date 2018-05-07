<?php
/**
 * TOP API: alibaba.aliqin.fc.iot.sms.send request
 * 
 * @author auto create
 * @since 1.0, 2017.11.13
 */
class AlibabaAliqinFcIotSmsSendRequest
{
	/** 
	 * 公共回传参数，在“消息返回”中会透传回该参数；举例：用户可以传入自己下级的会员ID，在消息返回时，该会员ID会包含在内，用户可以根据该会员ID识别是哪位会员使用了你的应用
	 **/
	private $extend;
	
	/** 
	 * 短信接收号码。
	 **/
	private $recNum;
	
	/** 
	 * 短信模板变量，传参规则{"key":"value"}，key的名字须和申请模板中的变量名一致，多个变量之间以逗号隔开。示例：针对模板“验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！”，传参时需传入{"code":"1234","product":"alidayu"}
	 **/
	private $smsParam;
	
	/** 
	 * 短信模板ID，传入的模板必须是在阿里大于“管理中心-短信模板管理”中的可用模板。示例：SMS_585014
	 **/
	private $smsTemplateCode;
	
	/** 
	 * 短信类型，传入值请填写normal
	 **/
	private $smsType;
	
	private $apiParas = array();
	
	public function setExtend($extend)
	{
		$this->extend = $extend;
		$this->apiParas["extend"] = $extend;
	}

	public function getExtend()
	{
		return $this->extend;
	}

	public function setRecNum($recNum)
	{
		$this->recNum = $recNum;
		$this->apiParas["rec_num"] = $recNum;
	}

	public function getRecNum()
	{
		return $this->recNum;
	}

	public function setSmsParam($smsParam)
	{
		$this->smsParam = $smsParam;
		$this->apiParas["sms_param"] = $smsParam;
	}

	public function getSmsParam()
	{
		return $this->smsParam;
	}

	public function setSmsTemplateCode($smsTemplateCode)
	{
		$this->smsTemplateCode = $smsTemplateCode;
		$this->apiParas["sms_template_code"] = $smsTemplateCode;
	}

	public function getSmsTemplateCode()
	{
		return $this->smsTemplateCode;
	}

	public function setSmsType($smsType)
	{
		$this->smsType = $smsType;
		$this->apiParas["sms_type"] = $smsType;
	}

	public function getSmsType()
	{
		return $this->smsType;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.iot.sms.send";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->recNum,"recNum");
		RequestCheckUtil::checkNotNull($this->smsTemplateCode,"smsTemplateCode");
		RequestCheckUtil::checkNotNull($this->smsType,"smsType");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
