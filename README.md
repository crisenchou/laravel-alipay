# laravel-alipay
alipay of laravel5



扫码支付
$alipay = new AlipayPrecreate();
$bizContent =[
    'out_trade_no'=>time(),
    'totle_amount'=>1,
    'body'=>'test goods',
    'subjiect'=>'test'
]
$alipay->setBizContent($bizContent);

/*
$alipay->setOutTradeNo();
$alipay->setTotalAmount();
$alipay->setSubject();
$alipay->setBody();
*/
$qrcode = $alipay->getPayUrl();
echo $qrcode;
