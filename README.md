# laravel-alipay

> 支付宝扫码支付

## 安装
> composer require "crisen/laravel-alipay":"dev-master"

## 文档

1. 注册服务提供者  config目录下app.php文件

        'providers' => [  
        	...
        	Crisen\LaravelAlipay\AlipayServiceProvider::class,
        }

2. 添加门面

   ~~~
    'aliases' => [
    	...
   	'Alipay' => Crisen\LaravelAlipay\Facades\Alipay::class,
   ]
   ~~~

3. 配置文件

        php artisan vendor publish 

## 使用方法

### 扫码支付

~~~
 	$alipay = Alipay::factory('precreate');
    $alipay->setBizContent([
        'out_trade_no' => $outTradeNo,
        'subject' => 'test',
        'total_amount' => 1,
        'body' => 'test goods',
    ])->send();
 	if($alipay->isSuccessful() && $alipay->isTradeStatusOk()){
  		 $codeUrl = $alipay->getCodeUrl();
  		 echo $codeUrl;
 	}
~~~

### 订单查询

~~~
	$alipay = Alipay::factory('query');
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
 $alipay = Alipay::factory('notify')->options($request->all());
 if ($alipay->isPaid()) {
    // echo $alipay->getOutTradeNo();
 } 
~~~~



## License

MIT