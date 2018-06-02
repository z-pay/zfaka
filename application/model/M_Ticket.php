<?php
/**
 * File: ticket.php
 * Functionality: 工单
 * Author: sq
 * Date: 2016-03-21
 */

class M_Ticket extends Model {

	function __construct() {
		$this->table = TB_PREFIX.'ticket';
		parent::__construct();
	}


}