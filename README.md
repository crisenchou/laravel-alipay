# laravel-alipay
alipay of laravel5



扫码支付
$alipay = new AlipayPrecreate();
$alipay->setOutTradeNo();
$alipay->setTotalAmount();
$alipay->setSubject();
$alipay->setBody();
$qrcode = $alipay->getPayUrl();
echo $qrcode;
