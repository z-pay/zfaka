<?php
/**
 * TOP API: alibaba.aliqin.fc.iot.device.isexist request
 * 
 * @author auto create
 * @since 1.0, 2017.03.13
 */
class AlibabaAliqinFcIotDeviceIsexistRequest
{
	/** 
	 * 设备类型（预留将来扩展）
	 **/
	private $deviceType;
	
	/** 
	 * 设备编号
	 **/
	private $imei;
	
	/** 
	 * 渠道扩展编码（预留）
	 **/
	private $midPatChannel;
	
	private $apiParas = array();
	
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
		return "alibaba.aliqin.fc.iot.device.isexist";
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
