<?php


/*
* config
*/


return [
    'gateway' => 'https://openapi.alipaydev.com/gateway.do',//沙箱环境
    //'gateway' => 'https://openapi.alipay.com/gateway.do',
    //支付宝公钥
    'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB",
    'appid' => '2016091000475096',
    'notify_url' => '',
    'version' => '1.0',//调用的接口版本，固定为：1.0 	1.0
    'merchant_private_key' => 'your sign',//商户请求参数的签名串
    'sign_type' => 'RSA',//商户生成签名字符串所使用的签名算法类型，目前支持RSA 	RSA
    'charset' => 'utf-8',//请求使用的编码格式
    //私钥证书路径
    'cert_path' => 'C:\Users\crisen\.ssh\id_rsa'
];