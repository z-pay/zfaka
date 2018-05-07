<?php

/*
 * 功能：会员中心－登录类
 * author:资料空白
 * time:20150902
 */

class LoginController extends PcBasicController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        if (false != $this->login AND $this->userid) {
            $this->redirect("/member/center/");
            return FALSE;
        }
        //对refererUrl进行有效性校验
        $referer_url = $this->get('referer_url', false);
        $sign = $this->get('sign', false);
        if (md5(URL_KEY . $referer_url) === $sign) {
            $data['referer_url'] = $referer_url;
        } else {
            $data['referer_url'] = '';
        }
        $data['cookie_mobile'] = $this->getCookie('mobile');
        $this->getView()->assign($data);
    }
}