<?php
/**
 * Created by PhpStorm.
 * User: crisen
 * Date: 2016/12/14
 * Time: 17:36
 */

namespace Crisen\LaravelAlipay\payment;


class AlipayNotify extends Alipay
{
    public $request;

    public function __construct($request)
    {
        $config = config('alipay');
        $this->setPublicKey($config['alipay_public_key']);
    }

    public function isPaid()
    {
        $sign = $this->request['sign'];
        unset($this->request['sign']);
        unset($this->request['sign_type']);
        $data = $this->getSignContent($this->response);
        if ($this->verifySign($data, $sign)) {
            if (isset($this->request['trade_status ']) && 'TRADE_SUCCESS' == $this->request['trade_status ']) {
                return true;
            }
            return false;
        } else {
            info('notify sign error');
            return false;
        }
    }

    public function getRequestData()
    {
        return $this->request;
    }


    public function getOutTradeNo()
    {
        return $this->request['out_trade_no'];
    }

}