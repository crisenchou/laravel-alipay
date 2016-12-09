# laravel-alipay

> alipay of laravel5

## Installation
> composer require "crisen/laravel-alipay":"dev-master"

## Document

1. register service providor

         Crisen\LaravelAlipay\AlipayServiceProvider::class,
   
2.  publish

        php  artisan vendor publish 
         
3. usage

        $alipay = new AlipayPrecreate(); 
        $bizContent =[
            'out_trade_no'=>time(),
            'totle_amount'=>1,
            'body'=>'test goods',
            'subjiect'=>'test'
        ];
        $alipay->setBizContent($bizContent);
        $qrcode = $alipay->getPayUrl();
        echo $qrcode;
