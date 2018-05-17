<?php

namespace app\api\service;
use app\api\model\Goods;
use app\api\model\Collection as CollectionModel;
use app\lib\exception\CollectionException;

class Collection
{
    public static function changeCollection($goods_id, $collected)
    {
        // 查找 goods 是否存在
        // 存在
        // 将 goods_id 和 user_id 保存到 collected表
        $goods = Goods::getNormalGoods($goods_id);
        $uid = Token::getCurrentUid();
        $collection = CollectionModel::getCollection($goods_id, $uid,$collected);
        if ($collection) {  //如果存在，更新
            if($collected->collected != $collected){    //校验收藏状态是否与数据库一致
                throw new CollectionException([
                    'msg' => '状态已修改'
                ]);
            }
            else{
                $userId = $collection->user_id;
                $goodsId = $collection->goods_id;
                $result = $collection->update(['collected' => $collected], ['user_id' => $uid, 'goods_id' => $goods_id]);
            }
        } else {   // 不存在，添加数据
            $collection = new self();
            $collection->goods_id = $goods_id;
            $collection->user_id = $uid;
            $result = $collection->save(['collected' => $collected]);
        }
        $result = Goods::incOrDecCollectCount($goods_id, $collected);
        return $result;
    }
}