<?php
/**
 * TOP API: alibaba.aliqin.fc.voice.record.geturl request
 * 
 * @author auto create
 * @since 1.0, 2017.08.09
 */
class AlibabaAliqinFcVoiceRecordGeturlRequest
{
	/** 
	 * 一次通话的唯一标识id
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
		return "alibaba.aliqin.fc.voice.record.geturl";
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
