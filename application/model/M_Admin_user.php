<?php
/**
 * File: M_Admin_user.php
 * Functionality: 用户 model
 * Author: 资料空白
 * Date: 2015-9-4
 */

class M_Admin_user extends Model
{

    function __construct()
    {
        $this->table = TB_PREFIX . 'admin_user';
        parent::__construct();
    }

    public function checkLogin($email, $password)
    {
        if (strlen($email) > 0 AND strlen($password) > 0) {
            $field = array('id', 'email', 'secret', 'password');
            $where = array('email' => $email);
            $result = $this->Field($field)->Where($where)->SelectOne();
            if (is_array($result) AND !empty($result)) {
                if (password($password, $result['secret']) === $result['password']) {
                    return $result;
                }
            }
        }
        return FALSE;
    }

}