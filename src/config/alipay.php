<?php


/*
* config
*/


return [
    'gateway' => 'https://openapi.alipaydev.com/gateway.do',//沙箱环境
    //'gateway' => 'https://openapi.alipay.com/gateway.do',//正式环境
    'appid' => '2016091000475096',//支付宝appid
    'notify_url' => '', // 通知地址
    //私钥证书路径
    'cert_path' => 'C:\Users\crisen\.ssh\id_rsa',
    //支付宝公钥
    'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB",

];