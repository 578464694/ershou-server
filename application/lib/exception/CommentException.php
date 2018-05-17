<?php
namespace app\lib\exception;

class CommentException extends BaseException
{
    protected $code = 402;
    protected $msg = '评论失败';
    protected $errorCode = 50000;
}