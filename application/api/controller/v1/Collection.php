<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\CollectionValidate;
use app\api\model\Collection as CollectionModel;
use app\lib\exception\CollectionException;
use app\lib\message\SuccessMessage;
use think\Exception;
use think\Request;

class Collection extends BaseController
{
    public $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'changeCollection']
    ];

    /**
     * 修改商品收藏状态
     * @return \think\response\Json
     */
    public function changeCollection()
    {
        $collectionValidate = new CollectionValidate();
        $collectionValidate->goCheck();
        $param = $collectionValidate->getParameterByRule(input('post.'));
        $collected = $param['collected'] ? 1 : 0;
        $goods_id = $param['id'];
        try{
            $result = CollectionModel::changeCollection($goods_id,$collected);
            if($result){
                return json(new SuccessMessage(),201);
            }
            else{
                throw new CollectionException();
            }
        }catch (Exception $e){
            throw $e;
        }
    }


}