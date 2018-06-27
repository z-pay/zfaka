<?php

/*
 * 功能：安装模块
 * Author:资料空白
 * Date:20180626
 */

class SetponeController extends BasicController
{

	public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
		if(file_exists(INSTALL_LOCK)){
			$this->redirect("/product/");
			return FALSE;
		}else{
			$data = array();
			$require = array(
				array('name' => 'PHP版本','require'=>'7.0.0','result'=>$this->_isVersion('7.0.0',phpversion())),
				array('name' => 'Curl支持','require'=>'必须','result'=>$this->_isfun('curl_init')),
				
			);
			
			$data['require'] = $require;
			$this->getView()->assign($data);
		}
    }
	
	private function _isfun($funName = '')
	{
		if (!$funName || trim($funName) == '' || preg_match('~[^a-z0-9\_]+~i', $funName, $tmp)) return '错误';
		return (false !== function_exists($funName)) ? '<font color="green">√</font>' : '<font color="red">×</font>';
	}
	
	private function _isVersion($version = '',$required_version = '')
	{
		return (false !== version_compare( $version, $required_version, '>=' )) ? '<font color="green">√</font>' : '<font color="red">×</font>';
	}
}