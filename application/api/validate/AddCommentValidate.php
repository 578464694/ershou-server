<?php
namespace app\api\validate;

class AddCommentValidate extends BaseValidate
{
    protected $rule = [
        'goods_id' => 'require|isPositiveInteger',
        'content' => 'require|length:1,140'
    ];

    protected $message = [
        'content.length' => '评论长度应在 1-140 之间'
    ];
}