<?php

namespace Crisen\LaravelAlipay\payment;


abstract class Alipay{
    
    protected $appid;//支付宝appid
    protected $method;//
    protected $format='json';
    protected $charset='utf-8';
    protected $sign_type='RSA';
    protected $sign;
    protected $timestamp;
    protected $version='1.0';
    protected $notify_url;
    protected $app_auth_token;
    protected $biz_content;
    abstract protected function setBizContent();
}