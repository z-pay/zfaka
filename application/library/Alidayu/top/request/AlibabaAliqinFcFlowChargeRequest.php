<?php
/**
 * TOP API: alibaba.aliqin.fc.flow.charge request
 * 
 * @author auto create
 * @since 1.0, 2017.06.23
 */
class AlibabaAliqinFcFlowChargeRequest
{
	/** 
	 * 需要充值的流量
	 **/
	private $grade;
	
	/** 
	 * 当scope=0时，is_province=true为指定分省通道。默认值为false
	 **/
	private $isProvince;
	
	/** 
	 * 唯一流水号
	 **/
	private $outRechargeId;
	
	/** 
	 * 手机号
	 **/
	private $phoneNum;
	
	/** 
	 * 充值原因
	 **/
	private $reason;
	
	/** 
	 * 0:全国漫游流量  1:省内流量。不填默认值为0
	 **/
	private $scope;
	
	private $apiParas = array();
	
	public function setGrade($grade)
	{
		$this->grade = $grade;
		$this->apiParas["grade"] = $grade;
	}

	public function getGrade()
	{
		return $this->grade;
	}

	public function setIsProvince($isProvince)
	{
		$this->isProvince = $isProvince;
		$this->apiParas["is_province"] = $isProvince;
	}

	public function getIsProvince()
	{
		return $this->isProvince;
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

	public function setPhoneNum($phoneNum)
	{
		$this->phoneNum = $phoneNum;
		$this->apiParas["phone_num"] = $phoneNum;
	}

	public function getPhoneNum()
	{
		return $this->phoneNum;
	}

	public function setReason($reason)
	{
		$this->reason = $reason;
		$this->apiParas["reason"] = $reason;
	}

	public function getReason()
	{
		return $this->reason;
	}

	public function setScope($scope)
	{
		$this->scope = $scope;
		$this->apiParas["scope"] = $scope;
	}

	public function getScope()
	{
		return $this->scope;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.flow.charge";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->grade,"grade");
		RequestCheckUtil::checkNotNull($this->outRechargeId,"outRechargeId");
		RequestCheckUtil::checkNotNull($this->phoneNum,"phoneNum");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
