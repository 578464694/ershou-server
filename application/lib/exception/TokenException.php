<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17
 * Time: 16:56
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    protected $code = 401;
    protected $msg = 'Token已过期或 Token无效';
    protected $errorCode = 10001;
}