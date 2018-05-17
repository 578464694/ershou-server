<?php
namespace app\api\validate;


class GetToken extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message = [
        'code.isNotEmpty' => 'code不能为空'
    ];
}