<?php

namespace Crisen\LaravelAlipay\payment;


class AlipayPrecreate extends Alipay
{

    public $response = [];

    public function __construct()
    {
        $config = config('alipay');
        $this->setAppid($config['appid']);
        $this->setNotifyUrl($config['notify_url']);
        $this->setVersion($config['version']);
        $this->setSignType($config['sign_type']);
        $this->setGateway($config['gateway']);
        $this->setMethod('alipay.trade.precreate');
        $this->setPublicKey($config['alipay_public_key']);
        if (!!$config['cert_path']) {
            $this->setRSAPrivateFilePath($config['cert_path']);
        } else {
            $this->setPrivateKey($config['merchant_private_key']);
        }
    }

    public function isSuccessful()
    {
        if (array_key_exists('alipay_trade_precreate_response', $this->response)) {
            return true;
        }
        return false;
    }

    public function isTradeStatusOk()
    {
        $response = $this->response['alipay_trade_precreate_response'];
        if (10000 == $response['code']) {
            return true;
        }
        return false;
    }

    public function getCodeUrl()
    {
        $response = $this->response['alipay_trade_precreate_response'];
        return $response['qr_code'];
    }

    public function send()
    {
        $this->setSysParams();
        $this->setSign();
        $response = $this->httpPost($this->getGateway(), $this->getSysParams());
        $response = json_decode($response, true);
        $this->response = $response;
    }

}