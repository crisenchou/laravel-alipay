<?php
/**
 * Created by PhpStorm.
 * User: crisen
 * Date: 2016/12/16
 * Time: 9:43
 */

namespace Crisen\LaravelAlipay;

use Mockery\CountValidator\Exception;

class AlipayFactory
{

    public static function factory($gateway)
    {
        $classname = 'Crisen\LaravelAlipay\payment\Alipay' . ucfirst($gateway);
        if (class_exists($classname)) {
            return new $classname;
        } else {
            throw new Exception('gateway is wrong');
        }
    }
}