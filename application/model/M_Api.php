<?php
/**
 * File: M_Api.php
 * Functionality: 用户 model
 * Author: 资料空白
 * Date: 2018-05-21
 */

class M_Api extends Model {

	function __construct() {
		$this->table = TB_PREFIX.'api';
		parent::__construct();
	}

}