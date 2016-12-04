<?php

namespace Crisen\LaravelAlipay\payment;


class AlipayPrecreate extends Alipay{
    
    private $method = 'alipay.trade.precreate';//

    protected function __construct(){
        $config = config_path('alipay.php');
        $this->appid = $config['appid'];
        $this->notify_url = $config['notify_url'];
        $this->version = $config['version'];
        $this->sign = $config['sign'];
        $this->sign_type = $config['sign_type'];
        $this->charset = $config['charset'];
    }
    

    public function getPayUrl(){
        $this->setSysParams();
        $this->setSign();
        $responce = $this->httpPost($this->gateway,$this->sysParams);
        if(isset($responce['qrcode'])){
            return $responce['qrcode'];
        }else{
            //exception
        }
    }
    
}