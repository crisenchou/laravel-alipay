<?php


/*
* config
*/


return [
    'gateway'=>'https://openapi.alipay.com/gateway.do',
    'appid'=>'your appid',
    'notify_url'=>'your appid',
    'version'=>'1.0',//调用的接口版本，固定为：1.0 	1.0
    'sign'=>'your sign',//商户请求参数的签名串
    'sign_type'=>'RSA',//商户生成签名字符串所使用的签名算法类型，目前支持RSA 	RSA
    'charset'=>'utf-8',//请求使用的编码格式
];








