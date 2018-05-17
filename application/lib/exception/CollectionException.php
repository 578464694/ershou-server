<?php
namespace app\lib\exception;

class CollectionException extends BaseException
{
    protected $code = 402;
    protected $msg = '商品收藏状态修改失败';
    protected $errorCode = 70000;
}