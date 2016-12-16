<?php
/**
 * Created by PhpStorm.
 * User: crisen
 * Date: 2016/12/16
 * Time: 9:43
 */

namespace Crisen\LaravelAlipay;


class AlipayFactory
{

    public static function factory($gateway)
    {
        if (class_exists($gateway)) {
            return new $gateway;
        } else {
            info('class not exist');
        }
    }

//    public static function __callStatic($name, $arguments)
//    {
//
//    }

}