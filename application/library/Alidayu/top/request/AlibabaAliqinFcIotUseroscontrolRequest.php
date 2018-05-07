<?php
/**
 * TOP API: alibaba.aliqin.fc.iot.useroscontrol request
 * 
 * @author auto create
 * @since 1.0, 2017.05.25
 */
class AlibabaAliqinFcIotUseroscontrolRequest
{
	/** 
	 * 用户停开的操作类型：MANAGE_RESUME、MANAGE_STOP
	 **/
	private $action;
	
	/** 
	 * 物联卡的iccid
	 **/
	private $iccid;
	
	private $apiParas = array();
	
	public function setAction($action)
	{
		$this->action = $action;
		$this->apiParas["action"] = $action;
	}

	public function getAction()
	{
		return $this->action;
	}

	public function setIccid($iccid)
	{
		$this->iccid = $iccid;
		$this->apiParas["iccid"] = $iccid;
	}

	public function getIccid()
	{
		return $this->iccid;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.iot.useroscontrol";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->action,"action");
		RequestCheckUtil::checkNotNull($this->iccid,"iccid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
