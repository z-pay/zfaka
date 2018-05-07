<?php
/**
验证函数集合
*/

//检查是否为邮箱格式
if (!function_exists('isEmail')){
	function isEmail($email) {
		if (!$email) {
			return false;
		}

		return preg_match('/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,4}$/', $email);
	}
}
//检查是否为代码
if (!function_exists('isPostalCode')){
	function isPostalCode($postalCode) {
		if (!$postalCode) {
			return false;
		}

		return preg_match("/^[1-9]\d{5}$/", $postalCode);
	}
}
//检查是否IP地址
if (!function_exists('isIPAddress')){
	function isIPAddress($IPAddress) {
		if (!$IPAddress) {
			return false;
		}
		return preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])" .
	                    "(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $IPAddress);
	}
}
//检查是否为身份证号码
if (!function_exists('isIDCard')){
	function isIDCard($IDCard) {
		if (!$IDCard) {
			return false;
		}
		return preg_match('/(^([\d]{15}|[\d]{18}|[\d]{17}x)$)/', $IDCard);
	}
}

//检查中文
if (!function_exists('isCn')){
	function isCn($str){
		if(preg_match("/[\x{4e00}-\x{9fa5}]+/u", $str)) {
			return true;
		}
		return false;
	}
}
/**
 * 检查数字
 * @param string $str 标签字符串
 */
if (!function_exists('isNumber')){
	function isNumber($str){
		if(preg_match('/^\d+$/', $str)) {
			return true;
		}
		return false;
	}
}
/**
 * 检查是否每位相同
 * @param string $str 标签字符串
 */
if (!function_exists('isNumSame')){
	function isNumSame($str){
		if(preg_match('/^(\w)\1+$/', $str)) {
			return true;
		}
		return false;
	}
}
/**
 * 检查是否为空
 * @param string $str 标签字符串
 */
if (!function_exists('isEmpty')){
	function isEmpty($str){
		if(preg_match('/^\s*$/', $str)) {
			return true;
		}
		return false;
	}
}

/**
 * 检测是否为合法url
 */
if (!function_exists('isUrl')){
	function isUrl($url){
	    if(!preg_match('/http[s]?:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
	        return false;
	    }
	    return true;
	}
}

if (!function_exists('isPhoneNumber')){
	function isPhoneNumber($phone) {
		if (!$phone) {
			return false;
		}
		return preg_match('/^((\(d{2,3}\))|(\d{3}\-))?1(1|3|4|5|6|7|8|9)\d{9}$/', $phone);
	}
}

if (!function_exists('isAreaCode')){
	function isAreaCode($code){
		if (!$code) {
			return false;
		}

		return preg_match('/^(0\d{3})|(0\d{2})$/', $code);
	}
}

//验证银行卡
if (!function_exists('isBankNo')){
    function isBankNo($banknumber){
        if ($banknumber == null || $banknumber == ""){
            return false;
        }
		//取出最后一位
		$last = substr($banknumber,(strlen($banknumber) - 1), 1);
        //前15或18位
        $front_last = substr($banknumber,0, strlen($banknumber) - 1);
        $front_arr = Array();
        //将前置部分号码存入数组(前15或18位)
		$i= strlen($front_last) - 1;
        for ($i ; $i > -1; $i--) {
            //前15或18位倒序存进数组
			array_push($front_arr,substr($front_last,$i, 1));
        }
        $sum1 = $sum2 = $sum3 = 0;
        for ($j = 0; $j < count($front_arr); $j++) {
			
            if (($j + 1) % 2 == 1) {
                // 奇数数字和
                if (intval ($front_arr[$j]) * 2 < 9) {
                    $sum1 += intval($front_arr[$j]) * 2;
                } else {
                    $str = intval($front_arr[$j]) * 2;
                    $str1 = 1;
                    $str2 = $str % 10;
					$sum2 += $str1;
                    $sum2 += $str2;
                }
            } else {
                // 偶数数字和
                $sum3 += intval($front_arr[$j]);
            }
        }
         $sum = $sum1 + $sum2 + $sum3;
         $luhn = $sum % 10 == 0 ? 0 : 10 - $sum % 10;
        if ($luhn == intval($last)) {
            return true;
        } else {
            return false;
        }
    }
}