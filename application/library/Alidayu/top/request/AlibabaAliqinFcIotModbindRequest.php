<?php
/**
 * TOP API: alibaba.aliqin.fc.iot.modbind request
 * 
 * @author auto create
 * @since 1.0, 2017.03.17
 */
class AlibabaAliqinFcIotModbindRequest
{
	/** 
	 * 物联卡和iccid保持一致
	 **/
	private $billReal;
	
	/** 
	 * 物联卡业务：若无特殊为ICCID
	 **/
	private $billSource;
	
	/** 
	 * iccid （20位）
	 **/
	private $iccid;
	
	/** 
	 * 目前绑定的设备imei
	 **/
	private $imei;
	
	/** 
	 * 若无特殊物联卡传入122
	 **/
	private $midPatChannel;
	
	/** 
	 * 换绑的时候必传，换的新设备imei
	 **/
	private $newimei;
	
	/** 
	 * chgBind：换绑；unBind：解绑
	 **/
	private $opionType;
	
	private $apiParas = array();
	
	public function setBillReal($billReal)
	{
		$this->billReal = $billReal;
		$this->apiParas["bill_real"] = $billReal;
	}

	public function getBillReal()
	{
		return $this->billReal;
	}

	public function setBillSource($billSource)
	{
		$this->billSource = $billSource;
		$this->apiParas["bill_source"] = $billSource;
	}

	public function getBillSource()
	{
		return $this->billSource;
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

	public function setImei($imei)
	{
		$this->imei = $imei;
		$this->apiParas["imei"] = $imei;
	}

	public function getImei()
	{
		return $this->imei;
	}

	public function setMidPatChannel($midPatChannel)
	{
		$this->midPatChannel = $midPatChannel;
		$this->apiParas["mid_pat_channel"] = $midPatChannel;
	}

	public function getMidPatChannel()
	{
		return $this->midPatChannel;
	}

	public function setNewimei($newimei)
	{
		$this->newimei = $newimei;
		$this->apiParas["newimei"] = $newimei;
	}

	public function getNewimei()
	{
		return $this->newimei;
	}

	public function setOpionType($opionType)
	{
		$this->opionType = $opionType;
		$this->apiParas["opion_type"] = $opionType;
	}

	public function getOpionType()
	{
		return $this->opionType;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.iot.modbind";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->billReal,"billReal");
		RequestCheckUtil::checkNotNull($this->billSource,"billSource");
		RequestCheckUtil::checkNotNull($this->iccid,"iccid");
		RequestCheckUtil::checkNotNull($this->imei,"imei");
		RequestCheckUtil::checkNotNull($this->midPatChannel,"midPatChannel");
		RequestCheckUtil::checkNotNull($this->opionType,"opionType");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
