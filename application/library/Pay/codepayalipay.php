<?php
/**
 * File: codepayalipay.php
 * Functionality: 码支付-支付宝扫码支付
 * Author: 资料空白
 * Date: 2018-6-29
 */
namespace Pay;
class codepayalipay
{
	private $apiHost="http://api2.fateqq.com:52888/creat_order/?";
	
	//处理请求
	public function pay($payconfig,$params)
	{
		$parameter = array(
			"id" => (int)$codepay_config['id'],//平台ID号
			"type" => $type,//支付方式
			"price" => (float)$price,//原价
			"pay_id" => $pay_id, //可以是用户ID,站内商户订单号,用户名
			"param" => $param,//自定义参数
			"act" => (int)$codepay_config['act'],//此参数即将弃用
			"outTime" => (int)$codepay_config['outTime'],//二维码超时设置
			"page" => (int)$codepay_config['page'],//订单创建返回JS 或者JSON
			"return_url" => $codepay_config["return_url"],//付款后附带加密参数跳转到该页面
			"notify_url" => $codepay_config["notify_url"],//付款后通知该页面处理业务
			"style" => (int)$codepay_config['style'],//付款页面风格
			"pay_type" => $codepay_config['pay_type'],//支付宝使用官方接口
			"user_ip" => getIp(),//付款人IP
			"qrcode_url" => $codepay_config['qrcode_url'],//本地化二维码
			"chart" => trim(strtolower($codepay_config['chart']))//字符编码方式
			//其他业务参数根据在线开发文档，添加参数.文档地址:https://codepay.fateqq.com/apiword/
			//如"参数名"=>"参数值"
		);
		
		$back = create_link($parameter, $payconfig['key'],$payconfig['gateway']); //生成支付URL
		if (function_exists('file_get_contents')) { //如果开启了获取远程HTML函数 file_get_contents
			$codepay_json = file_get_contents($back['url']); //获取远程HTML
		} else if (function_exists('curl_init')) {
			$ch = curl_init(); //使用curl请求
			$timeout = 5;
			curl_setopt($ch, CURLOPT_URL, $back['url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$codepay_json = curl_exec($ch);
			curl_close($ch);
		}
		$codepay_data = json_decode($codepay_json);
		$qr = $codepay_data ? $codepay_data->qrcode : '';
		
	}
	
	
	//处理返回
	public function notifyProcess($payconfig,$params)
	{
		ksort($params); //排序post参数
		reset($params); //内部指针指向数组中的第一个元素
		$sign = '';
		foreach ($params AS $key => $val) {
			if ($val == '') continue;
			if ($key != 'sign') {
				if ($sign != '') {
					$sign .= "&";
					$urls .= "&";
				}
				$sign .= "$key=$val"; //拼接为url参数形式
				$urls .= "$key=" . urlencode($val); //拼接为url参数形式
			}
		}
		if (!$params['pay_no'] || md5($sign . $payconfig['security_code']) != $params['sign']) { //不合法的数据 KEY密钥为你的密钥
			exit('fail');
		} else { //合法的数据
			//业务处理
			
			exit('success');
		}
	}
	
	
	/**
	 * 加密函数
	 * @param $params 需要加密的数组
	 * @param $codepay_key //码支付密钥
	 * @param string $host //使用哪个域名
	 * @return array
	 */
	private function _create_link($params, $codepay_key, $host = "")
	{
		ksort($params); //重新排序$data数组
		reset($params); //内部指针指向数组中的第一个元素
		$sign = '';
		$urls = '';
		foreach ($params AS $key => $val) {
			if ($val == '') continue;
			if ($key != 'sign') {
				if ($sign != '') {
					$sign .= "&";
					$urls .= "&";
				}
				$sign .= "$key=$val"; //拼接为url参数形式
				$urls .= "$key=" . urlencode($val); //拼接为url参数形式
			}
		}

		$key = md5($sign . $codepay_key);//开始加密
		$query = $urls . '&sign=' . $key; //创建订单所需的参数
		$apiHost = ($host ? $host : $this->apiHost); //网关
		$url = $apiHost . $query; //生成的地址
		return array("url" => $url, "query" => $query, "sign" => $sign, "param" => $urls);
	}
}
