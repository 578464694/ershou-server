<?php
namespace app\api\validate;


class IdMustBePositivceInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
    ];

}