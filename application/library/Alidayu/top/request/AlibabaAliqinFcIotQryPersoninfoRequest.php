<?php
/**
 * TOP API: alibaba.aliqin.fc.iot.qry.personinfo request
 * 
 * @author auto create
 * @since 1.0, 2017.03.03
 */
class AlibabaAliqinFcIotQryPersoninfoRequest
{
	/** 
	 * 需要查询的iccid
	 **/
	private $iccid;
	
	/** 
	 * 由系统根据业务分配
	 **/
	private $midPatChannel;
	
	/** 
	 * 指定查询某userid
	 **/
	private $userid;
	
	private $apiParas = array();
	
	public function setIccid($iccid)
	{
		$this->iccid = $iccid;
		$this->apiParas["iccid"] = $iccid;
	}

	public function getIccid()
	{
		return $this->iccid;
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

	public function setUserid($userid)
	{
		$this->userid = $userid;
		$this->apiParas["userid"] = $userid;
	}

	public function getUserid()
	{
		return $this->userid;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.iot.qry.personinfo";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->iccid,"iccid");
		RequestCheckUtil::checkNotNull($this->midPatChannel,"midPatChannel");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
