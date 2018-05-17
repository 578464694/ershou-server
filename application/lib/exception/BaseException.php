<?php
namespace app\lib\exception;

use think\Exception;

class BaseException extends Exception
{
    protected $code = 400;
    protected $msg = '参数错误';
    protected $errorCode = 10000;

    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return;
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode'];
        }
    }

    /**
     * 获得 http 状态码
     * @return http 状态码
     */
    public function getHttpCode()
    {
        return $this->code;
    }

    public function getMsg()
    {
        return $this->msg;
    }

    public function setMsg($msg) {
        $this->msg = $msg;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}