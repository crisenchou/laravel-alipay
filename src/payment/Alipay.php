<?php

namespace Crisen\LaravelAlipay\payment;

use Exception;

abstract class Alipay
{

    private $appid;//支付宝appid
    private $method;
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
        return $this;
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
        $this->sysParams['app_id'] = $this->appid;
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
        $this->sysParams['biz_content'] = json_encode($this->biz_content, JSON_UNESCAPED_UNICODE);
        return $this->sysParams;
    }


    protected function getSignContent($params)
    {
        ksort($params);
        $stringToBeSigned = '';
        $flag = '';
        foreach ($params as $k => $v) {
            if (false === $this->isEmpty($v)) {
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
        $sign = $this->sign($string);
        $this->sysParams['sign'] = $sign;
        return $sign;
    }

    protected function verifySign($data, $sign)
    {
        $pubkey = $this->publicKey;
        $res = "-----BEGIN RSA PUBLIC KEY-----\n" .
            wordwrap($pubkey, 64, "\n", true) .
            "\n-----END RSA PUBLIC KEY-----";
        if (!$res) {
            throw new Exception('您使用的公钥格式错误');
        }
        return openssl_verify($data, base64_decode($sign), $res);
    }

    private function sign($data)
    {
        if ($this->isEmpty($this->rsaPrivateKeyFilePath)) {
            $priKey = $this->rsaPrivateKey;
            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        } else {
            $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $res = openssl_get_privatekey($priKey);
        }

        if (!$res) {
            throw new Exception('您使用的私钥格式错误');
        }

        openssl_sign($data, $sign, $res);
        if (!$this->isEmpty($this->rsaPrivateKeyFilePath)) {
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

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($response, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $response;

    }

    protected function httpGet($url)
    {
        return $this->httpRequest($url);
    }


    protected function httpPost($url, $data)
    {
        return $this->httpRequest($url, $data);
    }

    protected function isEmpty($value)
    {
        return !$value;
    }

    protected function validateParams()
    {
        //check params
    }

    public function send()
    {
        $this->setSysParams();
        $this->setSign();
        $this->validateParams();
        $response = $this->httpPost($this->getGateway(), $this->getSysParams());
        $response = json_decode($response, true);
        $this->response = $response;
        return $this;
    }

}