<?php
/**
 * File: M_User.php
 * Functionality: 用户 model
 * Author: 资料空白
 * Date: 2018-05-21
 */

class M_User extends Model {

	function __construct() {
		$this->table = TB_PREFIX.'user';
		parent::__construct();
	}

	/**
	 * 登录
	 * @param string $email
	 * @param string $password
	 * @return params on success or 0 or failure
	 */
	public function checkLogin($email, $password){
		$field = array('id', 'email','password','nickname','groupid');
		if(isEmail($email)){
			$where = array('email' => $email);
		}else{
			return FALSE;
		}
		$result=$this->Field($field)->Where($where)->SelectOne();
		if($result){
			if(md5($password)===$result['password']){
				unset($result['password']);
				$this->_session($result);
				return $result;
			}
		}
		return FALSE;
	}



	/**
	* 登录session处理
	* @param $params
	* @return 1
	*/
	private function _session($params){
		if(!empty($params)){
			$params['expiretime']= time() + 15*60;
			\Yaf\Session::getInstance()->__set('uinfo', $params);
		}
	}

}