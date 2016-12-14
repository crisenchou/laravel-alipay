<?php
/**
 * Created by PhpStorm.
 * User: crisen
 * Date: 2016/12/14
 * Time: 17:18
 */

namespace Crisen\LaravelAlipay\payment;


class AlipayQuery extends Alipay
{

    public function __construct()
    {
        $config = config('alipay');
        $this->setAppid($config['appid']);
        $this->setVersion($config['version']);
        $this->setSignType($config['sign_type']);
        $this->setGateway($config['gateway']);
        $this->setMethod('alipay.trade.query');
        $this->setPublicKey($config['alipay_public_key']);
        if (!!$config['cert_path']) {
            $this->setRSAPrivateFilePath($config['cert_path']);
        } else {
            $this->setPrivateKey($config['merchant_private_key']);
        }
    }

    public function isSuccessful()
    {
        if (array_key_exists('alipay_trade_query_response', $this->response)) {
            return true;
        }
        return false;
    }

    public function isTradeStatusOk()
    {
        $response = $this->response['alipay_trade_query_response'];
        if (10000 == $response['code']) {
            return true;
        }
        return false;
    }

    public function getRequestData()
    {
        return $this->response['alipay_trade_query_response'];
    }

}