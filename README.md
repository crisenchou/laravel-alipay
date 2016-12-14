# laravel-alipay

> alipay of laravel5

## 安装
> composer require "crisen/laravel-alipay":"dev-master"

## 文档

1. 注册服务提供者

         Crisen\LaravelAlipay\AlipayServiceProvider::class,

2. 配置文件

        php  artisan vendor publish 

## 使用方法

### 扫码支付

~~~
 $alipay = new AlipayPrecreate(); 
 $bizContent =[
     'out_trade_no'=>time(),
     'totle_amount'=>1,
     'body'=>'test goods',
     'subjiect'=>'test'
 ];
 $alipay->setBizContent($bizContent)->send();
 if($alipay->isSuccessful() && $alipay->isTradeStatusOk()){
   $codeUrl = $alipay->getCodeUrl();
   echo $codeUrl;
 }
~~~

### 订单查询

~~~
$alipay = new AlipayQuery();
$bizCOntent = [
  'out_trade_no' => 'xxx'//数据库中的订单号
];
$alipay->setBizContent($bizContent)->send();
if($alipay->isSuccessful() && $alipay->isTradeStatusOk()){
   //dd($alipay->getRequestData();
 }
~~~

### 异步通知

~~~~
 $alipay = new AlipayNotify($request->all());
 if ($alipay->isPaid()) {
    // echo $alipay->getOutTradeNo();
 } 
~~~~



## License

MIT