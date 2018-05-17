<?php
namespace app\lib\exception;

class UserException extends BaseException
{
    protected $code = 404;
    protected $msg = '用户不存在';
    protected $errorCode = 60000;
}