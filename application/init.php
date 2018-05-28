<?php
date_default_timezone_set('Asia/Chongqing');
define('ENV', strtoupper(ini_get('yaf.environ')));
define('URL_KEY', 'faG2#@@1');
define('CUR_DATE', date('Y-m-d'));
define('CUR_DATETIME', date('Y-m-d H:i:s'));
define('CUR_TIMESTAMP', time());
define('TB_PK',        'id');
define('TB_PREFIX',    't_');
define('LIB_PATH',     APP_PATH.'/application/library/');
define('CORE_PATH',    LIB_PATH.'/Core/');
define('MODEL_PATH',   APP_PATH.'/application/model/');
define('FUNC_PATH',    APP_PATH.'/application/function/');
define('COMMON_PATH', APP_PATH.'/application/views/common/');
define('TEMP_PATH',    APP_PATH.'/temp/');
define('UPLOAD_PATH', APP_PATH.'/public/upload/');
define('LOG_FILE',APP_PATH.'/log/php/'.CUR_DATE.'.log');
define('REQUEST_FILE',APP_PATH.'/log/request/'.CUR_DATE.'.log');
define('SQL_FILE',APP_PATH.'/log/sqld/'.CUR_DATE.'.log');
define('CRONTAB_FILE',APP_PATH.'/log/crontab/'.CUR_DATE.'.log');

error_reporting(0);
error_reporting(E_ALL ^E_NOTICE);
ini_set('display_errors', 'off');
ini_set('display_startup_errors',1);//php启动错误信息
ini_set('log_errors', 'on');
ini_set('error_log', LOG_FILE);
define('DEBUG',FALSE );//调试模式关闭

define('HOME_URL','https://www.mtrp2p.com/');//主站点地址
define('IMG_URL','https://res.mtrp2p.com/');//图片资源地址
define('SITE_URL','https://www.mtrp2p.com/');//当前站点外部地址(当前站点)
define('RPC_URL','http://192.168.1.134:9004');//RPC服务器地址(RPC服务)
define('MOBILE_URL','https://m.mtrp2p.com');//移动端站点地址(微信、移动端)
define('ADMIN_URL','https://admin.mtrp2p.com');//管理后台站点地址(后台)
