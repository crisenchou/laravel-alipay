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
        if (is_array($this->request) && array_key_exists('alipay_trade_precreate_response', $this->request)) {
            $sign = $this->request['sign'];
            $data = $this->request['alipay_trade_precreate_response'];
            if ($this->verifySign(json_encode($data), $sign)) {
                return true;
            }
            info('sign error');
            return false;
        }
        return false;
    }

    public function isTradeStatusOk()
    {
        $response = $this->request['alipay_trade_precreate_response'];
        if (10000 == $response['code']) {
            return true;
        }
        return false;
    }

    public function getRequestData()
    {
        return $this->request['alipay_trade_precreate_response'];
    }

    public function getCodeUrl()
    {
        $response = $this->request['alipay_trade_precreate_response'];
        return $response['qr_code'];
    }

}