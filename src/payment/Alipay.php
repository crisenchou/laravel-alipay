<?php

namespace Crisen\LaravelAlipay\payment;

use Exception;

abstract class Alipay
{

    private $appid;//支付宝appid
    private $method;//
    private $format = 'json';
    private $charset = 'utf-8';
    private $publicKey;
    private $privateKey;
    private $sign_type = 'RSA';
    private $version = '1.0';
    private $notify_url;
    private $app_auth_token;
    private $biz_content = [];
    private $gateway;
    private $sysParams = [];
    private $rsaPrivateKeyFilePath;


    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    public function setAppid($appid)
    {
        $this->appid = $appid;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function setSignType($signType)
    {
        $this->sign_type = $signType;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notify_url = $notifyUrl;
    }

    public function setAppAuthToken($appAuthToken)
    {
        $this->app_auth_token = $appAuthToken;
    }

    public function setBizContent($bizContent)
    {
        $this->biz_content = $bizContent;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function setPublicKey($key)
    {
        $this->publicKey = $key;
    }

    public function setPrivateKey($key)
    {
        $this->privateKey = $key;
    }

    public function setRSAPrivateFilePath($path)
    {
        if (!!$path) {
            $this->rsaPrivateKeyFilePath = $path;
        }
    }

    public function getGateway()
    {
        return $this->gateway;
    }


    public function __get($key)
    {
        return isset($this->$key) ? $this->$key : '';
    }

    public function __set($key, $value)
    {
        if (isset($this->$key)) {
            $this->$key = $value;
        } else {
            throw new Exception('propoty not exist');
        }
    }

    public function getSysParams()
    {
        return $this->sysParams;
    }

    protected function setSysParams()
    {
        $this->sysParams['appid'] = $this->appid;
        $this->sysParams['method'] = $this->method;
        $this->sysParams['format'] = $this->format;
        $this->sysParams['sign_type'] = $this->sign_type;
        $this->sysParams['timestamp'] = date("Y-m-d H:i:s");
        $this->sysParams['version'] = $this->version;
        $this->sysParams['charset'] = $this->charset;
        if ($this->notify_url) {
            $this->sysParams['notify_url'] = $this->notify_url;//可选项
        }
        if ($this->app_auth_token) {
            $this->sysParams['app_auth_token'] = $this->app_auth_token;//可选项
        }
        $this->sysParams['biz_content'] = $this->biz_content;
        return $this->sysParams;
    }


    protected function getSignContent($params)
    {
        ksort($params);
        $stringToBeSigned = '';
        $flag = '';
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                $stringToBeSigned .= $flag . "$k" . "=" . "$v";
                $flag = '&';
            }
        }
        unset ($k, $v);

        return $stringToBeSigned;
    }


    protected function setSign()
    {
        $string = $this->getSignContent($this->sysParams);
        $sign = $this->getSign($string);
        $this->sysParams['sign'] = $sign;
        return $sign;
    }

    protected function checkSign()
    {

    }

    private function getSign($data)
    {
        if ($this->checkEmpty($this->rsaPrivateKeyFilePath)) {
            $priKey = $this->rsaPrivateKey;
            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        } else {
            $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $res = openssl_get_privatekey($priKey);
        }

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        openssl_sign($data, $sign, $res);

        if (!$this->checkEmpty($this->rsaPrivateKeyFilePath)) {
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }


    protected function httpRequest($url, $post = null)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (!empty($post)) {
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

    protected function httpGet($url)
    {
        return $this->httpRequest($url);
    }


    protected function httpPost($url, $data)
    {
        return $this->httpRequest($url, $data);
    }


    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value)
    {
        return (!!$value);
    }

    /*
    * RS 签名
    */
    public function rsaEncrypt($string, $rsaPublicKeyPem)
    {
        //读取公钥文件
        $pubKey = file_get_contents($rsaPublicKeyPem);
        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);
        $chrtext  = null;
        $encodes  = [];
        foreach ($string as $n => $block) {
            if (!openssl_public_encrypt($string, $chrtext , $res)) {
                echo "<br/>" . openssl_error_string() . "<br/>";
            }
            $encodes[] = $chrtext ;
        }
        $chrtext = implode(",", $encodes);

        return $chrtext;
    }

    public function rsaDecrypt($data, $rsaPrivateKeyPem)
    {
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