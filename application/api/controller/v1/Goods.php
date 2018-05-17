<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Goods as GoodsModel;
use app\api\validate\AddGoodsValidate;
use app\api\validate\IdMustBePositivceInt;
use app\api\validate\PaginateValidate;
use app\lib\exception\GoodsException;
use app\lib\message\SuccessMessage;
use app\api\service\Goods as GoodsService;

class Goods extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'addGoods,getCollection']
    ];

    /**
     * 添加商品
     * @return \think\response\Json
     * @throws GoodsException
     */
    public function addGoods()
    {
        (new AddGoodsValidate())->goCheck('addGoods');
        $params = input('post.');
        $result = GoodsModel::createGoods($params);
        if($result){
            return json(new SuccessMessage(),201);
        }
        else{
            throw new GoodsException([
                'msg' => '商品添加失败'
            ]);
        }
    }

    /**
     * 分页获得商品
     * @param int $page
     * @param int $size
     * @return \think\Paginator
     * @throws GoodsException
     */
    public function getGoods($page = 1,$size = 15)
    {
        (new PaginateValidate())->goCheck('goods');
        $goods = GoodsModel::getGoodsWithCollect($page,$size);
        if(!$goods){
            throw new GoodsException([
               'msg' => '商品不存在'
            ]);
        }
        else{
            return $goods;
        }
    }

    /**
     * 获得商品详情
     * @param int $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws GoodsException
     */
    public function getGoodsDetail($id=0)
    {
        (new IdMustBePositivceInt())->goCheck();
        $goods = GoodsModel::getGoodsDetail($id);
        if(!$goods) {
            throw new GoodsException();
        }
        else{
            return $goods;
        }
    }

    public function test()
    {
        $result = GoodsModel::getGoodsWithCollect(2);
        return $result;
    }

    public function modifyGoodsPrice($id = -1,$sale_price = 0)
    {
        (new AddGoodsValidate())->goCheck('modifySalePrice');
        $result = GoodsService::modifyGoodsPrice($id,$sale_price);
        if($result){
            return json(new SuccessMessage(),201);
        }
        else{
            throw new GoodsException([
                'msg' => '价格修改失败',
                'code' => 402,
                'errorCode' => 70001
            ]);
        }
    }

    /**
     * 已售出
     * @param int $goods_id
     * @return \think\response\Json
     * @throws GoodsException
     */
    public function signGoodsSaled($id = -1){
        (new IdMustBePositivceInt())->goCheck('goods');
        $result = GoodsService::signGoodsSaled($id);
        if($result){
            return json(new SuccessMessage(),201);
        }
        else{
            throw new GoodsException([
                'msg' => '标记售出失败',
                'code' => 402,
                'errorCode' => 70002
            ]);
        }
    }

    public function getCollection($page = 1,$size = 15)
    {
        $result = GoodsModel::getCollectedGoods($page,$size);
        return $result;
    }

}