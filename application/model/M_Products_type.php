<?php
/**
 * File: M_Products_type.php
 * Functionality: 产品分类 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Products_type extends Model
{

	public function __construct()
	{
		$this->table = TB_PREFIX.'products_type';
		parent::__construct();
	}

}