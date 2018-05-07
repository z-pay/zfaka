<?php
/**
 * TOP API: alibaba.aliqin.fc.iot.rechargeCard request
 * 
 * @author auto create
 * @since 1.0, 2017.12.01
 */
class AlibabaAliqinFcIotRechargeCardRequest
{
	/** 
	 * iccid的值
	 **/
	private $billReal;
	
	/** 
	 * 外部计费号类型：写‘ICCID’
	 **/
	private $billSource;
	
	/** 
	 * 生效时间,1000,立即生效; AUTO_ORD,下周期自动续订
	 **/
	private $effCode;
	
	/** 
	 * yyyy-MM-dd HH:mm:ss
	 **/
	private $effTime;
	
	/** 
	 * ICCID
	 **/
	private $iccid;
	
	/** 
	 * 流量包offerId
	 **/
	private $offerId;
	
	/** 
	 * 外部id,用来做幂等
	 **/
	private $outRechargeId;
	
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

	public function setEffCode($effCode)
	{
		$this->effCode = $effCode;
		$this->apiParas["eff_code"] = $effCode;
	}

	public function getEffCode()
	{
		return $this->effCode;
	}

	public function setEffTime($effTime)
	{
		$this->effTime = $effTime;
		$this->apiParas["eff_time"] = $effTime;
	}

	public function getEffTime()
	{
		return $this->effTime;
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

	public function setOfferId($offerId)
	{
		$this->offerId = $offerId;
		$this->apiParas["offer_id"] = $offerId;
	}

	public function getOfferId()
	{
		return $this->offerId;
	}

	public function setOutRechargeId($outRechargeId)
	{
		$this->outRechargeId = $outRechargeId;
		$this->apiParas["out_recharge_id"] = $outRechargeId;
	}

	public function getOutRechargeId()
	{
		return $this->outRechargeId;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.iot.rechargeCard";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->billReal,"billReal");
		RequestCheckUtil::checkNotNull($this->billSource,"billSource");
		RequestCheckUtil::checkNotNull($this->effCode,"effCode");
		RequestCheckUtil::checkNotNull($this->iccid,"iccid");
		RequestCheckUtil::checkNotNull($this->offerId,"offerId");
		RequestCheckUtil::checkNotNull($this->outRechargeId,"outRechargeId");
		RequestCheckUtil::checkMaxLength($this->outRechargeId,32,"outRechargeId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
