<?php
/*
 * CLI 的入口文件
 * CLI 下一般用于执行定时脚本或任务
 */
header('content-Type:text/html;charset=utf-8;');
define('APP_PATH',  realpath(dirname(__FILE__) . '/../'));
//函数
function getClientIP(){
	if(isset($_SERVER['HTTP_ALI_CDN_REAL_IP']) AND $_SERVER['HTTP_ALI_CDN_REAL_IP']){
		$ip = $_SERVER["HTTP_ALI_CDN_REAL_IP"]; 
	}elseif ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) { 
		$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]; 
	}elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) { 
		$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"]; 
	}elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]) { 
		$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
	}elseif (getenv("HTTP_X_FORWARDED_FOR")) { 
		$ip = getenv("HTTP_X_FORWARDED_FOR"); 
	}elseif (getenv("HTTP_CLIENT_IP")) { 
		$ip = getenv("HTTP_CLIENT_IP"); 
	}elseif (getenv("REMOTE_ADDR")){ 
		$ip = getenv("REMOTE_ADDR"); 
	}else { 
		$ip = "Unknown"; 
	}
	$ip_array=explode(',',$ip);
	return $ip_array[0];
} 
Yaf\Loader::import(APP_PATH.'/application/init.php');
$app = new Yaf\Application(APP_PATH.'/conf/application.ini');
$app->bootstrap()->getDispatcher()->dispatch(new Yaf\Request\Simple());