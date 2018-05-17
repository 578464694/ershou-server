<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/20
 * Time: 11:56
 */

namespace app\lib\exception;


class GoodsException extends BaseException
{
    protected $code = 404;
    protected $msg = '商品不存在，请检查ID';
    protected $errorCode = 80001;
}