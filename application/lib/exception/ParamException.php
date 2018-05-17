<?php
namespace app\lib\exception;


class ParamException extends BaseException
{
    protected $code = 400;
    protected $msg = '参数错误';
    protected $errorCode = 10000;
}