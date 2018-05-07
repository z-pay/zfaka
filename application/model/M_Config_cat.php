<?php
/**
 * File: media.php
 * Functionality: 
 * Author: sq
 * Date: 2016-03-21
 */

class M_Config_cat extends Model {

	function __construct() {
		$this->table = TB_PREFIX.'config_cat';
		parent::__construct();
	}       
}