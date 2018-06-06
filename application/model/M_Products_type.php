<?php
/**
 * File: M_Seo.php
 * Functionality: SEO优化设置 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Products_type extends Model {

	function __construct() {
		$this->table = TB_PREFIX.'products_type';
		parent::__construct();
	}

}