<?php
// Generate specific lenght random chars or numbers, or both
if ( ! function_exists('getRandom')){
    function getRandom($length = 4, $type = 1) {
        switch ($type) {
            case 1:
                $string = '1234567890';
            break;

            case 2:
                $string = 'abcdefghijklmnopqrstuvwxyz';
            break;

            case 3:
                $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;

            case 4:
                $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;

            case 5:
                $string = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        }
        $output = '';
        for ($i = 0; $i < $length; $i++) {
            $pos = mt_rand(0, strlen($string) - 1);
            $output .= $string[$pos];
        }
        return $output;
    }
}

// Convert a string to an array
if ( ! function_exists('stringToArray')){
	function stringToArray($string) {
		$length = strlen($string);

		$arr = array();
		for ($i = 0; $i < $length; $i++) {
			$arr[] = $string[$i];
		}

		return $arr;
	}
}

/**
 * 字符串转数组
 * @param string $tags 标签字符串
 * @return array $array 数组数组
 */
if ( ! function_exists('string2array')){
	function string2array($tags) {
		return preg_split('/\s*,\s*/', trim($tags), -1, PREG_SPLIT_NO_EMPTY);
	}
}

/**
 * 数组转字符串
 * @param array $tags 标签数组
 * @return string $string 标签字符串
 */
if ( ! function_exists('array2string')){
	function array2string($tags) {
		return implode(',', $tags);
	}
}

/**************************************************************
*
*  将数组转换为JSON字符串（兼容中文）
*  @param  array   $array      要转换的数组
*  @return string      转换得到的json字符串
*  @access public
*
*************************************************************/
if ( ! function_exists('arrayRecursive')){
function arrayRecursive(&$array, $function, $apply_to_keys_also = false){
	static $recursive_counter = 0;
	if (++$recursive_counter > 1000) {
		die('possible deep recursion attack');
	}

	if($array){
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				arrayRecursive($array[$key], $function, $apply_to_keys_also);
			} else {
				$array[$key] = $function($value);
			}

			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
	    }
	}
    $recursive_counter--;
}
}
if ( ! function_exists('JSON')){
function JSON($array) {
	arrayRecursive($array, 'urlencode', TRUE);
	$json = json_encode($array);
	return urldecode($json);
}
}

/***********************************************************
 * 截取中文字符串
 **********************************************************/
if ( ! function_exists('subString_UTF8')){
	function subString_UTF8($str, $start, $lenth){
		$len = strlen($str);
		$r = array();
		$n = 0;
		$m = 0;
		for($i = 0; $i < $len; $i++) {
			$x = substr($str, $i, 1);
			$a  = base_convert(ord($x), 10, 2);
			$a = substr('00000000'.$a, -8);
			if ($n < $start){
				if (substr($a, 0, 1) == 0) {
				}elseif (substr($a, 0, 3) == 110) {
					$i += 1;
				}elseif (substr($a, 0, 4) == 1110) {
					$i += 2;
				}
				$n++;
			}else{
				if (substr($a, 0, 1) == 0) {
					$r[ ] = substr($str, $i, 1);
				}elseif (substr($a, 0, 3) == 110) {
					$r[ ] = substr($str, $i, 2);
					$i += 1;
				}elseif (substr($a, 0, 4) == 1110) {
					$r[ ] = substr($str, $i, 3);
					$i += 2;
				}else{
					$r[ ] = '';
				}
				if (++$m >= $lenth){
					break;
				}
			}
		}
		return implode('',$r);
	}
}


/**
 * 处理时间加减
 * $invest_time [投标时间]
 */
if ( ! function_exists('time_minus')){
	function time_minus($invest_time){
		$sys_time_str = time();
		$invest_time_str = strtotime($invest_time);
		$result_time_str = $sys_time_str-$invest_time_str;
		$year = floor($result_time_str/60/60/24/365);
		$day = floor($result_time_str/60/60/24);
		$hour = floor($result_time_str/60/60);
		$minute = floor($result_time_str/60);
		$second = floor($result_time_str);

		if($year!=0){
			return $year;
		}elseif($day!=0){
			return $day."天";
		}elseif($hour!=0){
			return $hour."小时";
		}elseif($minute!=0){
			return $minute."分钟";
		}elseif($second!=0){
			return $second."秒";
		}
	}
}


//处理金额 中文 亿 万显示
if (!function_exists('convertMoney')){
	function convertMoney($money){
		$str='';
		if(is_numeric($money) AND $money>0){
			$yi=floor($money/100000000);
			if($yi>0){
				$str.=$yi.'<b>亿</b>';
				$str.=number_format(($money%100000000)/10000,2,'.','');
			}else{
				$str.=number_format($money/10000,2,'.','');
			}
			$str.='<b>万</b>';
        }else{
            $str='0.00';
        }
		return $str;
	}
}
//处理人数显示
if (!function_exists('convertNum')){
	function convertNum($num){
		$str='';
		if(is_numeric($num) AND $num>0){
			$yi=floor($num/100000000);
			if($yi>0){
				$str.=$yi.'<b>亿</b>';
				$str.=floor(($num%100000000)/10000);
				$str.='<b>万</b>';
				$str.=($num%100000000)%10000;
			}else{
				$wan=floor($num/10000);
				if ($wan) {
					$str.=$wan.'<b>万</b>';
				}
				$str.=$num%10000;
			}
        }else{
            $str='0';
        }
		return $str;
	}
}

//隐藏手机号
if (!function_exists('convertMobile')){
	function convertMobile($mobile) {
		$pattern = "/(1\d{1,2})\d\d(\d{0,3})/";
		$replacement = "\$1*****\$3";

		$mobile = preg_replace($pattern, $replacement, $mobile);
		return $mobile;
	}
}
//隐藏邮箱
if (!function_exists('convertEmail')){
	function convertEmail($email) {
		$email_array=explode('@',$email);
		$length=strlen($email_array[0]);
		switch ($length){
			case 1:
				$start=$email_array[0];
				break;
			case 2:
				$start=subString_UTF8($email_array[0], 0, 1).'*';
				break;
			case 3:
				$start=subString_UTF8($email_array[0], 0, 2).'*';
				break;
			default:
				$start=subString_UTF8($email_array[0], 0, 3);
				for($i = 0; $i < $length; $i++) {
					$start.='*';
				}
		}

		$email=$start.'@'.$email_array[1];
		return $email;
	}
}
//隐藏身份证
if (!function_exists('convertIdCard')){
	function convertIdCard($IdCard) {
		$pattern = "/(\d{0,4})\d\d\d\d\d\d\d\d(\d{0,3})/";
		$replacement = "\$1**********\$3";

		$IdCard = preg_replace($pattern, $replacement, $IdCard);
		return $IdCard;
	}
}

//隐藏银行卡号
if (!function_exists('convertBankCard')){
	function convertBankCard($BankCard) {
		$start=subString_UTF8($BankCard, 0, 6);
		$end=subString_UTF8($BankCard, strlen($BankCard)-3, 3);
		return $start.'**********'.$end;
	}
}

//隐藏营业执照号
if (!function_exists('convertBusCard')) {
	function convertBusCard($buscard){
		$start=substr($buscard, 0, 4);
		$end=substr($buscard, strlen($buscard)-3, 3);
		return $start.'******'.$end;
	}
}

//隐藏姓名
if (!function_exists('convertName')){
	function convertName($str){
		if(!preg_match("[^\x80-\xff]",$str)){
			$i=mb_strlen($str,'utf-8');
			$first=mb_substr($str,0,1,'utf-8');
			$middle='';
			if($i>2){
				$last=mb_substr($str,$i-1,1,'utf-8');
				for($k=1;$k<$i-1;$k++){
					$middle.="**";
				}
			}elseif($i==2){
				$last="**";
			}else{
				$last=mb_substr($str,1,1,'utf-8');
			}
			return $first.$middle.$last;
		}else{
			$i=strlen($str);
			$first=substr($str,0,1);
			$middle='';
			if($i>2){
				$last=substr($str,$i-1,1);
				for($k=1;$k<$i-1;$k++){
					$middle.="*";
				}
			}elseif($i=2){
				$last="**";
			}else{
				$last=substr($str,1,1);
			}
			return $first.$middle.$last;
		}
	}
}
//隐藏地址(上上签)
if (!function_exists('convertAddress')) {
	function convertAddress($str){
		if(!preg_match("[^\x80-\xff]",$str)){
			$i=mb_strlen($str,'utf-8');
			$first=mb_substr($str,0,3,'utf-8');
			$middle='';
			if($i>4){
				$last=mb_substr($str,$i-2,2,'utf-8');
				for($k=1;$k<$i-1;$k++){
					$middle.="*";
				}
			}else{
				$last='******';
			}
			return $first.$middle.$last;
		}else{
			$i=strlen($str);
			$first=substr($str,0,3);
			$middle='';
			if($i>4){
				$last=substr($str,$i-2,2,'utf-8');
				for($k=1;$k<$i-1;$k++){
					$middle.="*";
				}
			}else{
				$last='******';
			}
			return $first.$middle.$last;
		}
	}
}
//隐藏企业名称(上上签)
if (!function_exists('convertEnterprisename')) {
	function convertEnterprisename($str){
		$middle='******';
		if(!preg_match("[^\x80-\xff]",$str)){
			$i=mb_strlen($str,'utf-8');
			$first=mb_substr($str,0,5,'utf-8'); 
			$last=mb_substr($str,-4,4,'utf-8');
			return $first.$middle.$last;
		}else{ 
			$first=substr($str,0,5);
			$last=substr($str,-4,4,'utf-8');
			return $first.$middle.$last;
		}
	}
}
//根据身份证获取年龄和性别
if (!function_exists('getAgeAndSexByIDcard')){
	function getAgeAndSexByIDcard($idcard){
		$result = array('age' => '未知', 'sex' => '未知');
        if (strlen($idcard) == 15 OR strlen($idcard) == 18) {
            $result['sex'] = substr($idcard, (strlen($idcard) == 18 ? -2 : -1), 1) % 2 ? '男' : '女';
            //过了这年的生日才算多了1周岁
            $date = strtotime(substr($idcard, 6, 8));
            //获得出生年月日的时间戳
            $today = strtotime('today');
            //获得今日的时间戳
            $diff = floor(($today - $date) / 86400 / 365);
            //得到两个日期相差的大体年数
            //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
            $result['age'] = strtotime(substr($idcard, 6, 8) . ' +' . $diff . 'years') > $today ? ($diff + 1) : $diff;
        }
        return $result;
    }
}