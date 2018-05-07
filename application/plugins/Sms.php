<?php
/*
 * 发送短信插件
 */

class SmsPlugin extends Yaf\Plugin_Abstract
{

    private $m_sms_log;
    private $m_sms_templates;

    public function __construct()
    {
        $this->m_sms_log = Helper::load('sms_log');
        $this->m_sms_templates = Helper::load('sms_templates');
    }

    public function send($action = '', $mobile = '', $tplvalue = array())
    {
        if ($action AND $mobile AND $tplvalue) {
            //1.处理$action
            $sms_sql = $this->m_sms_templates->Where(array('action' => $action, 'type' =>0))->SelectOne();
            if (empty($sms_sql)) {
                $result = array('code' => 1003, 'msg' => '错误的Action');
            } else {
                //3.处理发送结果20170817新增，debug环境下，不发送
                if (DEBUG) {
                    $sms_result = array('code' => 1, 'message' => '成功');
                } else {
                    $l_sms=new \Alidayu\Alidayu();
					$sms_result = $l_sms->smsSend($mobile, $sms_sql['tplid'], $tplvalue);
                }
                //4.记录发送结果
				//4.1失败
                if ($sms_result['code'] > 1) {
					//连接处理结果
					$sms_message=isset($sms_result['message'])?$sms_result['message']:'';
					$sms_message.=isset($sms_result['sub_msg'])?$sms_result['sub_msg']:'';
					
                    $params = array(
                        'action' => $action,
                        'mobile' => $mobile,
                        'content' => implode(',', $tplvalue),
                        'ip' => getClientIP(),
                        'result' => $sms_message,
                        'addtime' => time(),
                        'status' => 0
                    );
                    $result = array('code' => $sms_result['code'], 'msg' => $sms_message);
				//4.2成功
                } else {
					$sms_message=isset($sms_result['message'])?$sms_result['message']:'';
                    $params = array(
                        'action' => $action,
                        'mobile' => $mobile,
                        'content' => implode(',', $tplvalue),
                        'ip' => getClientIP(),
                        'result' => $sms_message,
                        'addtime' => time(),
                        'status' => 1
                    );
                    $result = array('code' => 1, 'msg' => $sms_message);
                }
                $this->m_sms_log->Insert($params); //添加日志
            }
        } else {
            $result = array('code' => 1000, 'msg' => '丢失参数');
        }
        return $result;
    }
}