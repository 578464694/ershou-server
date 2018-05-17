<?php
namespace app\api\validate;

class CollectionValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'collected' => 'require|boolean'
    ];

    protected $message = [
        'goods_id.isPositiveInteger' => 'ID是正整数'
    ];
}