<?php
/**
 * Created by PhpStorm.
 * User: crisen
 * Date: 2016/12/16
 * Time: 9:41
 */

namespace Crissen\LaravelAlipay\Facades;


use Illuminate\Support\Facades\Facade;


class Alipay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Alipay';
    }
}