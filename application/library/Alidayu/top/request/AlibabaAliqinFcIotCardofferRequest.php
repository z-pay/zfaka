<?php
/**
 * TOP API: alibaba.aliqin.fc.iot.cardoffer request
 * 
 * @author auto create
 * @since 1.0, 2017.04.21
 */
class AlibabaAliqinFcIotCardofferRequest
{
	/** 
	 * 具体ICCID的值
	 **/
	private $billreal;
	
	/** 
	 * ICCID
	 **/
	private $billsource;
	
	private $apiParas = array();
	
	public function setBillreal($billreal)
	{
		$this->billreal = $billreal;
		$this->apiParas["billreal"] = $billreal;
	}

	public function getBillreal()
	{
		return $this->billreal;
	}

	public function setBillsource($billsource)
	{
		$this->billsource = $billsource;
		$this->apiParas["billsource"] = $billsource;
	}

	public function getBillsource()
	{
		return $this->billsource;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.iot.cardoffer";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->billreal,"billreal");
		RequestCheckUtil::checkNotNull($this->billsource,"billsource");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
