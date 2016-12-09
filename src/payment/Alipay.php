<?php

namespace Crisen\LaravelAlipay\payment;


abstract class Alipay{
    
    private $appid;//支付宝appid
    private $method;//
    private $format='json';
    private $charset='utf-8';
    private $fileCharset = "UTF-8";
    private $sign_type='RSA';
    private $sign;
    private $timestamp;
    private $version='1.0';
    private $notify_url;
    private $app_auth_token;
    private $biz_content = [];
    private $gateway;
    private $sysParams = [];
    
    
    public function setGateway($gateway){
        $this->gateway = $gateway;
    }
    
    public function setAppid($appid){
        $this->appid = $appid;
    }
    
    public function setFormat($format){
        $thisformat = $format;
    }
    
    public function setSignType($signType){
        $this->sign_type = $signType;
    }
    
    public function setVersion($version){
        $this->version = $version;
    }
    
    public function setNotifyUrl($notifyUrl){
        $this->notify_url = $notifyUrl;
    }
    
    public function setAppAuthToken($appAuthToken){
        $this->app_auth_token = $appAuthToken;
    }
    
    public function setBizContent($bizContent){
        $this->biz_content = $bizContent;
    }
    
    abstract public function setBizContent();
    
    
    protected function setSysParams(){
        $this->sysParams['appid'] = $this->appid;
        $this->sysParams['method'] = $this->method;
        $this->sysParams['format'] = $this->format;
        $this->sysParams['sign_type'] = $this->sign_type;
        $this->sysParams['timestamp'] = date("Y-m-d H:i:s");
        $this->sysParams['version'] = $this->appid;
        if($this->notify_url){
            $this->sysParams['notify_url'] = $this->notify_url;//可选项
        }
        if($this->app_auth_token){
            $this->sysParams['app_auth_token'] = $this->app_auth_token;//可选项
        }
        $this->sysParams['biz_content'] = $this->biz_content;
        return $this->sysParams;
    }
    
    
    protected function getSignContent($params) {
		ksort($params);
		$stringToBeSigned = '';
		$flag = '';
		foreach ($params as $k => $v) {
			if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
				// 转换成目标字符集
				//$v = $this->characet($v, $this->charset);
                $stringToBeSigned .= "$k" . "=" . "$v";
                $flag = '&';
			}
		}
		unset ($k, $v);
        
		return $stringToBeSigned;
	}
    
    
    protected function setSign(){
        $string = $this->getSignContent($this->sysParams);
        $sign = $this->getSign($string);
        $this->sysParams['sign'] = $sign;
        return $sign;
    }
    
    protected function checkSign(){
        
    }
    
    private function getSign($string){
        
    }
    
    
    protected function httpRequest($url, $post=null){
        
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if(!empty($post)){
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

		$reponse = curl_exec($ch);

		if (curl_errno($ch)) {

			throw new Exception(curl_error($ch), 0);
		} else {
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode) {
				throw new Exception($reponse, $httpStatusCode);
			}
		}

		curl_close($ch);
		return $reponse;
    }
    
    protected function httpGet($url){
        return $this->httpRequest($url);
    }
    
    
    protected function httpPost($url, $data){
        return $this->httpRequest($url, $data);
    }
    
    
    
    /**
	 * 转换字符集编码
	 * @param $data
	 * @param $targetCharset
	 * @return string
	 */
	function characet($data, $targetCharset) {
		
		if (!empty($data)) {
			$fileType = $this->fileCharset;
			if (strcasecmp($fileType, $targetCharset) != 0) {
				$data = mb_convert_encoding($data, $targetCharset, $fileType);
			}
		}
		return $data;
	}
    
    /**
	 * 校验$value是否非空
	 *  if not set ,return true;
	 *    if is null , return true;
	 **/
	protected function checkEmpty($value) {
		if (!isset($value))
			return true;
		if ($value === null)
			return true;
		if (trim($value) === "")
			return true;

		return false;
	}
    
    /*
    * RS 签名
    */
    public function rsaEncrypt($string, $rsaPublicKeyPem) {
		//读取公钥文件
		$pubKey = file_get_contents($rsaPublicKeyPem);
		//转换为openssl格式密钥
		$res = openssl_get_publickey($pubKey);
		$chrtext  = null;
		$encodes  = array();
		foreach ($string as $n => $block) {
			if (!openssl_public_encrypt($string, $chrtext , $res)) {
				echo "<br/>" . openssl_error_string() . "<br/>";
			}
			$encodes[] = $chrtext ;
		}
		$chrtext = implode(",", $encodes);

		return $chrtext;
	}

	public function rsaDecrypt($data, $rsaPrivateKeyPem) {
		//读取私钥文件
		$priKey = file_get_contents($rsaPrivateKeyPem);
		//转换为openssl格式密钥
		$res = openssl_get_privatekey($priKey);
		$decodes = explode(',', $data);
		$strnull = "";
		$dcyCont = "";
		foreach ($decodes as $n => $decode) {
			if (!openssl_private_decrypt($decode, $dcyCont, $res)) {
				echo "<br/>" . openssl_error_string() . "<br/>";
			}
			$strnull .= $dcyCont;
		}
		return $strnull;
	}
}