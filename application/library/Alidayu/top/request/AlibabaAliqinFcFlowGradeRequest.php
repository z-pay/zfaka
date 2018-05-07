<?php
/**
 * TOP API: alibaba.aliqin.fc.flow.grade request
 * 
 * @author auto create
 * @since 1.0, 2017.02.27
 */
class AlibabaAliqinFcFlowGradeRequest
{
	/** 
	 * 手机号码。如果未填手机号码，返回所有运营商的档位信息。如果填了手机号码，则返回此手机号码所属运营商的档位，如果手机号码对应的运营商未知，则返回所有档位
	 **/
	private $phoneNum;
	
	private $apiParas = array();
	
	public function setPhoneNum($phoneNum)
	{
		$this->phoneNum = $phoneNum;
		$this->apiParas["phone_num"] = $phoneNum;
	}

	public function getPhoneNum()
	{
		return $this->phoneNum;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.flow.grade";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
