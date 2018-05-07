<?php
/**
 * TOP API: alibaba.aliqin.fc.voice.num.cancelcall request
 * 
 * @author auto create
 * @since 1.0, 2017.11.20
 */
class AlibabaAliqinFcVoiceNumCancelcallRequest
{
	/** 
	 * 呼叫唯一id
	 **/
	private $callId;
	
	private $apiParas = array();
	
	public function setCallId($callId)
	{
		$this->callId = $callId;
		$this->apiParas["call_id"] = $callId;
	}

	public function getCallId()
	{
		return $this->callId;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.voice.num.cancelcall";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->callId,"callId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
