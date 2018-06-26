<?php

/*
 * 功能：安装模块
 * Author:资料空白
 * Date:20180626
 */

class SetptwoController extends BasicController
{

	public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		if(file_exists(APP_PATH.'/conf/install.lock')){
			$this->redirect("/product/");
			return FALSE;
		}else{
			$data = array();
			$this->getView()->assign($data);
		}
    }
	
	public function ajaxAction()
	{
		$host = $this->getPost('host',false);
		$port = $this->getPost('port',false);
		$user = $this->getPost('user', false);
		$password = $this->getPost('password', false);
		$dbname = $this->getPost('dbname', false);
		
		$data = array();
		
		if($host AND $port AND $user AND $password){
            try {
                $pdo = new PDO("mysql:host=".$host.";port=".$port.";charset=utf8;",$user, $password, array(PDO::ATTR_PERSISTENT => true,PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
				$isexists = $pdo->query("show databases like '{$dbname}'");
				$row = $isexists->fetch();
				print_r($row) ;
				foreach ($isexists as $row) {
					print_r($row) ;
				}
            } catch (PDOException $e) {
				$data = array('code' => 1001, 'msg' =>"失败:".$e->getMessage());
            }
		}else{
			$data = array('code' => 1000, 'msg' => '丢失参数');
		}
		Helper::response($data);
	}
}