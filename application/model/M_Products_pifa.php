<?php
/**
 * File: M_Products_pifa.php
 * Functionality: 产品pifa model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Products_pifa extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'products_pifa';
		parent::__construct();
	}
	
	public function getPifa($pid)
	{
		$result = array();
		$data = $this->Field(array('qty','money'))->Where(array('pid'=>$pid))->Select();
		if(!empty($data)){
			foreach($data AS $i){
				$k = $i['qty'];
				$result[$k] = $i['money'];
			}
		}
		return $result;
	}  
}