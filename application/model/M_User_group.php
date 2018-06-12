<?php
/**
 * File: M_User_group.php
 * Functionality: 用户分组 model
 * Author: 资料空白
 * Date: 2018-05-21
 */

class M_User_group extends Model
{
	public function __construct()
	{
		$this->table = TB_PREFIX.'user_group';
		parent::__construct();
	}
}