<?php

namespace Crisen\LaravelAlipay\payment;


class AlipayPrecreate extends Alipay
{

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

    public function getPayUrl()
    {
        $this->setSysParams();
        $this->setSign();
        $responce = $this->httpPost($this->getGateway(), $this->getSysParams());
        if (isset($responce['qrcode'])) {
            return $responce['qrcode'];
        } else {
            return $responce;
        }
    }

}