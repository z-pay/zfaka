<?php
/**
 * TOP API: alibaba.aliqin.fc.iot.device.post request
 * 
 * @author auto create
 * @since 1.0, 2017.03.15
 */
class AlibabaAliqinFcIotDevicePostRequest
{
	/** 
	 * 备注
	 **/
	private $comments;
	
	/** 
	 * 设备类型（将来扩展）
	 **/
	private $deviceType;
	
	/** 
	 * 15位imei号
	 **/
	private $imei;
	
	/** 
	 * 扩展字段
	 **/
	private $midPatChannel;
	
	private $apiParas = array();
	
	public function setComments($comments)
	{
		$this->comments = $comments;
		$this->apiParas["comments"] = $comments;
	}

	public function getComments()
	{
		return $this->comments;
	}

	public function setDeviceType($deviceType)
	{
		$this->deviceType = $deviceType;
		$this->apiParas["device_type"] = $deviceType;
	}

	public function getDeviceType()
	{
		return $this->deviceType;
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

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.iot.device.post";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->imei,"imei");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
