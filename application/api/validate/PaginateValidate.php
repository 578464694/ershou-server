<?php
namespace app\api\validate;

class PaginateValidate extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger|between:1,15',
        'goods_id' => 'require|isPositiveInteger'
    ];

    protected $message = [
      'page.isPositiveInteger' => 'page为正整数',
      'size.isPositiveInteger' => 'size为正整数',
    ];

    protected $scene = [
        'comments' => ['page','size','goods_id'],
        'goods' => ['page','size']
    ];
}