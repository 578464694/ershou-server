<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17
 * Time: 20:31
 */

namespace app\lib\message;


class SaveImageSuccess
{
    public $code = 201;
    public $msg = '图片保存成功';
    public $img_url = '';
//    public $short_imgs_url = '';

    function __construct($img_url)
    {
        $this->img_url = $img_url;
    }
}