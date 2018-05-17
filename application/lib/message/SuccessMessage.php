<?php
namespace app\lib\message;

use app\lib\exception\BaseException;

class SuccessMessage
{
    public $code = 201;
    public $msg = '操作成功';
    public $errorCode = 0;
}