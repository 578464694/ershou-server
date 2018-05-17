<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/14
 * Time: 11:17
 */

namespace app\lib\exception;


class UploadMsg
{
    public $code = 201;
    public $msg = '上传成功';
    public $url = '';
    public $errorMsg = 0;
    function __construct($url)
    {
        $this->url = $url;
    }
}