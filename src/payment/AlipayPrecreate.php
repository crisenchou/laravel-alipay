<?php

namespace Crisen\LaravelAlipay\payment;


class AlipayPrecreate extends Alipay{
    
    protected $method = 'alipay.trade.precreate';//
    protected $values = [];
    
    protected function setBizContent(){
        
    }

    protected function __construct(){
        $config = config_path('alipay.php');
        //do something
    }
    
    
    protected function setCommonParams(){
        $this->commonParams['appid'] = $this->appid;
    }
    
    protected function httpReqquest(){
        $response = [];
        //$response = post($this->values)
        // check $response;
        return $response;
    }
}