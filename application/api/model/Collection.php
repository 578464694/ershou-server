<?php
namespace app\api\model;

use app\api\service\Token;
use app\lib\exception\CollectionException;
use app\lib\exception\GoodsException;
use app\api\model\Goods as GoodsModel;
use think\Model;

class Collection extends BaseModel
{
    protected $hidden = ['create_time','update_time','user_id'];

    public function getCollectedAttr($value,$data)
    {
        return $this->integerConvertToBoolean($value,$data);
    }

    /**
     * 改变收藏状态
     * @param $goodsId
     * @param $collected
     * @return false|int
     * @throws GoodsException
     */
    public static function changeCollection($goods_id,$collected)
    {
        // 查找 goods 是否存在
        // 存在
        // 将 goods_id 和 user_id 保存到 collected表
//        $collection = new self();
        $goods = Goods::get($goods_id);
        if(!$goods){
            throw new GoodsException();
        }
        else{
            $uid = Token::getCurrentUid();
            $collection = self::getCollection($goods_id,$uid);
            if($collection){  //如果存在，更新
                $userId = $collection->user_id;
                $goodsId = $collection->goods_id;
                $result = $collection->update(['collected'=>$collected],['user_id' => $uid,'goods_id' => $goods_id]);
            }
            else{   // 不存在，添加数据
                $collection = new self();
                $collection->goods_id = $goods_id;
                $collection->user_id = $uid;
                $result = $collection->save(['collected'=>$collected]);
            }
            $result = GoodsModel::incOrDecCollectCount($goods_id,$collected);
            return $result;
        }
    }

    /**
     * 获得一条记录
     * @param $goods_id
     * @param $uid
     * @return array|false|\PDOStatement|string|Model
     */
    public static function getCollection($goods_id,$uid)
    {
        $result = self::where('goods_id','=',$goods_id)
                        ->where('user_id','=',$uid)
                        ->find();
        return $result;
    }



}