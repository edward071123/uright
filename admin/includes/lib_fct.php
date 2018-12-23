<?php
/**
 * 獲得用戶的真實IP地址
 *
 * @access  public
 * @return  string
 */
function real_ip(){
	static $realip = NULL;
	if ($realip !== NULL)
		return $realip;
	if (isset($_SERVER)){
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			/* 取X-Forwarded-For中第一個非unknown的有效IP字符串 */
			foreach ($arr AS $ip){
				$ip = trim($ip);
				if ($ip != 'unknown'){
					$realip = $ip;
					break;
				}
			}
		}else if (isset($_SERVER['HTTP_CLIENT_IP'])){
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		}else{
			if (isset($_SERVER['REMOTE_ADDR']))
				$realip = $_SERVER['REMOTE_ADDR'];
			else
				$realip = '0.0.0.0';
		}
	}else{
		if (getenv('HTTP_X_FORWARDED_FOR'))
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_CLIENT_IP'))
			$realip = getenv('HTTP_CLIENT_IP');
		else
			$realip = getenv('REMOTE_ADDR');
	}
	preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
	$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
	return $realip;
}
function userMessage($str, $url){
	echo "<meta charset='utf-8'>";
	echo "<script>";
	echo sprintf("alert('%s');", $str);
	echo sprintf("window.location='%s';", $url);
	echo "</script>";
}
function choiseMessage($str, $url1 , $url2){
	echo "<meta charset='utf-8'>";
	echo "<script>";
	echo sprintf("if (confirm('%s')) {", $str);
	echo sprintf("window.location='%s';", $url1);
	echo "} else {";
	echo sprintf("window.location='%s';", $url2);
	echo "}";
	echo "</script>";
}
function errMessageBack($str){
	echo "<meta charset='utf-8'>";
	echo "<script>";
	echo sprintf("alert('%s');", $str);
	echo sprintf("history.back(-1)");
	echo "</script>";
}

function txtFormat($str){
        $str = str_replace(chr(13).chr(10),"<br>",$str);
        return $str;
}
function decrypt($source,$todecrypt) {  
	//解密用的key，必須跟加密用的key一樣   
	$key = $source;  
	//解密前先解開base64碼
	$todecrypt = base64_decode($todecrypt);
	//使用3DES方法解密
	$encryptMethod = MCRYPT_TRIPLEDES;  
	//初始化向量來增加安全性 
	$iv = mcrypt_create_iv(mcrypt_get_iv_size($encryptMethod,MCRYPT_MODE_ECB), MCRYPT_RAND);  
	//使用mcrypt_decrypt函數解密，MCRYPT_MODE_ECB表示使用ECB模式  
	$decrypted_todecrypt = mcrypt_decrypt($encryptMethod, $key, $todecrypt, MCRYPT_MODE_ECB,$iv);
	//回傳解密後字串
	return $decrypted_todecrypt;  
}
?>