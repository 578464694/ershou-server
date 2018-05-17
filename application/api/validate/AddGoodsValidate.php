<?php
namespace app\api\validate;

use app\lib\exception\GoodsException;

class AddGoodsValidate extends BaseValidate
{
    protected $rule = [
        'contact_way' => 'require|in:QQ,微信,手机',
        'imgs_url' => 'length:1,4',
        'sale_price' => 'require|isPositiveFloat',
        'postage_free' => 'boolean',
        'is_knife' => 'boolean',
        'goods_id' => 'require|isPositiveInteger',
        'id' => 'require|isPositiveInteger',
    ];

    protected $message = [
        'sale_price.isPositiveFloat' => 'sell_price必须是正浮点数',
        'imgs_url' => 'imgs_url数组长度应在 1-4',
    ];

    protected $scene = [
        'modifySalePrice' => ['id','sale_price'],
        'addGoods' => ['imgs_url','sale_price','postage_free','is_knife'] 
    ];

    public function urlIsNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if(!is_array($value)){
            return false;
        }
        else{
            return true;
        }
    }
}