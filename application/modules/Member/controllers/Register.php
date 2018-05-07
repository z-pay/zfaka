<?php

/*
 * 功能：会员中心－注册类
 * author:资料空白
 * time:20150902
 */

class RegisterController extends PcBasicController
{

    private $m_user;

    public function init()
    {
        parent::init();
        $this->m_user = $this->load('user');
    }

    public function indexAction()
    {
        if (false != $this->login AND false != $this->userid) {
            $this->redirect("/member/center/");
            return FALSE;
        }
        $code = $this->get('code');
        $referer_mobile = '';
        if (is_numeric($code) AND $code > 0 AND ($code - 123456) > 0) {
            $reffer_userid = $code - 123456;
            $referer_mobile = $this->m_user->SelectFieldByID('mobilePhone', $reffer_userid);
        }
        $data['referer_mobile'] = $referer_mobile;
        //对refererUrl进行有效性校验
        $referer_url = $this->get('referer_url', false);
        $sign = $this->get('sign', false);
        if (md5(URL_KEY . $referer_url) === $sign) {
            $data['referer_url'] = $referer_url;
        } else {
            $data['referer_url'] = '';
        }
        $key = getRandom(20, 5);
        $this->setSession('registerKey', $key);
        $data['key'] = $key;
        $this->getView()->assign($data);
    }

    //代偿方注册    
    public function compensatoryAction()
    {
        if (false != $this->login AND false != $this->userid) {
            $this->redirect("/member/center/");
            return FALSE;
        }
        $code = $this->get('code');
        $referer_mobile = '';
        if (is_numeric($code) AND $code > 0 AND ($code - 123456) > 0) {
            $reffer_userid = $code - 123456;
            $referer_mobile = $this->m_user->SelectFieldByID('mobilePhone', $reffer_userid);
        }
        $data['referer_mobile'] = $referer_mobile;
        //对refererUrl进行有效性校验
        $referer_url = $this->get('referer_url', false);
        $sign = $this->get('sign', false);
        if (md5(URL_KEY . $referer_url) === $sign) {
            $data['referer_url'] = $referer_url;
        } else {
            $data['referer_url'] = '';
        }
        $key = getRandom(20, 5);
        $this->setSession('registerKey', $key);
        $data['key'] = $key;
        $this->getView()->assign($data);
    }

}