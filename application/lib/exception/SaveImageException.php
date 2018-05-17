<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17
 * Time: 20:26
 */

namespace app\lib\exception;


class SaveImageException extends BaseException
{
    protected $code = 402;
    protected $msg = '参数错误';
    protected $errorCode = 20000;
}