<?php
namespace app\api\service;

use app\api\model\Goods as GoodsModel;
use app\lib\enum\ScopeCollection;
use app\lib\exception\GoodsException;
use think\Db;

class Goods
{
    public static function addGoods($param)
    {

    }

    /**
     * 修改商品价格
     * @param $id
     * @param $price
     * @return $this
     * @throws GoodsException
     */
    public static function modifyGoodsPrice($id, $price)
    {
        $uid = Token::getCurrentUid();
        $goods = GoodsModel::get($id);
        if (!$goods) {
            throw new GoodsException();
        } else {
            if ($uid === $goods->user_id) {
                $result = GoodsModel::modifyGoodsSalePrice($id, $price);
                return $result;
            } else {
                throw new GoodsException([
                    'code' => 403,
                    'msg' => '非法操作，修改非自己的商品',
                    'errorCode' => 10003
                ]);
            }
        }
    }

    /**
     * 已出售
     * @param $id
     * @return $this
     * @throws GoodsException
     */
    public static function signGoodsSaled($id)
    {
        $uid = Token::getCurrentUid();
        $goods = GoodsModel::get($id);
        if (!$goods) {
            throw new GoodsException();
        } else {
            if ($uid === $goods->user_id) {
                $result = GoodsModel::modifyGoodsSaled($id);
                return $result;
            } else {
                throw new GoodsException([
                    'code' => 403,
                    'msg' => '非法操作，修改非自己的商品',
                    'errorCode' => 10003
                ]);
            }
        }
    }



}