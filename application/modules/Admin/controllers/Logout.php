<?php

/*
 * 功能：会员中心－退出类
 * author:资料空白
 * time:20150902
 */

class LogoutController extends AdminBasicController
{

    public function init()
    {
        parent::init();
        Yaf\Dispatcher::getInstance()->disableView();
        if (!$this->AdminUser) {
            $this->redirect('/admin/login');
            return FALSE;
        }
    }

    public function indexAction()
    {
        Yaf\Session::getInstance()->__unset('AdminUser');
        $this->redirect('/admin/login');
        return FALSE;
    }

}