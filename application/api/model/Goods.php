<?php
namespace app\api\model;

use app\api\service\Token;
use app\api\service\Token as TokenService;
use app\lib\enum\ScopeCollection;
use app\lib\enum\StatusEnum;
use app\lib\exception\GoodsException;
use app\lib\exception\ParamException;
use think\Db;
use think\Exception;

class Goods extends BaseModel
{
    protected $hidden = ['delete_time', 'update_time', 'user_id', 'from', 'status'];

    /**
     * 查询 MainImgUrl 预处理
     * url 为 MainImgUrl 字段的值
     * data 为这条记录
     * @param $url
     * @param $data
     * @return string
     */
    public function getMainImgUrlAttr($url, $data)
    {
        return $this->prefixUrl($url, $data);
    }

    /**
     * 关联 goodsimage表
     */
    public function imgs()
    {
        return $this->hasMany('GoodsImage', 'goods_id', 'id');
    }

    /**
     * 关联 user表
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id');
    }

    /**
     * 建立 user 多对多关联，中间表为 collection
     */
    public function userCollect()
    {
        return $this->hasMany('Collection', 'goods_id', 'id');
    }


    /**
     * 保存数据到数据库
     * 先保存 goods 表
     * 在保存到 image表
     * 保存到 goods_image表
     * @param $param
     * @return false|int
     */
    public static function createGoods($param)
    {
        $urls = self::formatShortUrls($param['imgs_url']); //???TODO::此处有问题
        $param['imgs_url'] = $urls;

        $gooodsInfo = self::formatGoodsInfo($param);
        $goods = new self();
        $result = $goods->save($gooodsInfo);
        Image::saveImages($param['imgs_url'], $goods->id);
        return $result;
    }

    /**
     * 格式化表单数据
     * @param $param
     * @return array
     */
    public static function formatGoodsInfo($param)
    {
//        $urls = self::formatImageUrl($param['imgs_url']);
        $goodsInfo = [
            'user_id' => TokenService::getCurrentUid(),
            'description' => $param['description'],
            'sale_price' => $param['sale_price'],
            'original_price' => $param['original_price'] ? $param['original_price'] : 0,
            'from' => 2,
            'main_img_url' => $param['imgs_url'][0],
            'postage_free' => $param['postage_free'] ? 1 : 0,   //是否包邮
            'can_be_knife' => $param['can_be_knife'] ? 1 : 0,    //可小刀
            'contact_way' => $param['contact_number'] ? $param['contact_way'] : "",
            'contact_number' => $param['contact_number'],
        ];
        return $goodsInfo;
    }

    /**
     * 截取 url
     * @param $urls
     */
    public static function formatShortUrls($urls)
    {
        if (!is_array($urls)) {
            $urls = (array)$urls;
        }
        for ($i = 0; $i < sizeof($urls); $i++) {
            $result = self::getShortUrl($urls[$i]);
            if ($result) {
                $urls[$i] = $result;
            } else {
                throw new ParamException([
                    'msg' => 'url参数错误'
                ]);
            }
        }
        return $urls;
    }

    /**
     * 匹配http://ershou.cn/images/20170828/6049857d4756986960ba7997550e5f2a.bmp
     * 中的 /20170828/6049857d4756986960ba7997550e5f2a.bmp
     * @param $subject
     * @return bool|mixed
     */
    public static function getShortUrl($subject)
    {
        $pattern = '/\/20[\d]{6}\/[0-9a-z]{32}.(jpg|png|ico|bmp)/';
        $matches = [];
        $result = preg_match($pattern, $subject, $matches);
        if ($result) {
            return $matches[0];
        } else {
            return false;
        }
    }

    public static function formatImageUrl($url)
    {
        return $url;
    }

    public static function getGoodsByPaginate($page = 1, $size = 15)
    {
        $goods = Goods::with(['user'])
            ->where('status', '=', StatusEnum::NORMAL)
            ->order('create_time', 'desc')
            ->paginate($size, true, [
                'page' => $page
            ]);
        $sql = self::getLastSql();
        return $goods;
    }

    /**
     * 获得商品详情
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getGoodsDetail($id)
    {
        $uid = TokenService::getCurrentUid();
        $goods = self::with(['imgs.imgUrl', 'user'])
            ->with(['userCollect' => function ($query) use ($uid) {
                $query->where('user_id', '=', $uid)
                    ->where('collected', '=', ScopeCollection::COLLECTED);
            }])
            ->where('id', '=', $id)
            ->where('status','=',StatusEnum::NORMAL)
            ->find();
        if(!$goods){
            throw new GoodsException();
        }
        if ($goods->user_id === $uid) {
            $goods['is_me'] = true;
        } else {
            $goods['is_me'] = false;
        }
        return $goods;
    }

    /**
     * 评论次数自增 1
     * @param $goodsId
     */
    public static function incOneAtCommentCount($goodsId)
    {
        $goods = self::where('id', '=', $goodsId)->inc('comment_count', 1)->update();
        return $goods;
    }

    /**
     * 获取商品，收藏排名靠前
     * @param $user_id
     * @param int $page
     * @param int $size
     * @return array
     */
    public static function getGoodsWithCollect($page = 1, $size = 15)
    {
        $user_id = TokenService::getCurrentUid();
        $result = self::with(
            ['userCollect' => function ($query) use ($user_id) {
                $query->where('user_id', '=', $user_id)
                    ->where('collected', '=', ScopeCollection::COLLECTED);
            }])
            ->with(['user'])
            ->where('status','=',StatusEnum::NORMAL)
            ->order('create_time', 'desc')
            ->paginate($size, true, [
                'page' => $page
            ])
            ->toArray();
        $tempArr = [];
        for ($i = sizeof($result) - 1; $i >= 0; $i--) {
            if (!empty($result[$i]['user_collect'])) {
                $temp = $result[$i];
                unset($result[$i]);
                array_push($tempArr, $temp);
            }
        }
        $result = array_merge($tempArr, $result);
        return $result;
    }

    /**
     * 收藏数减一或加一
     * @param $goods_id
     * @param $collected true 收藏,false 取消收藏
     */
    public static function incOrDecCollectCount($goods_id, $collected)
    {
        try {
            $result = '';
            if ($collected) { //收藏
                $result = self::where('id', '=', $goods_id)->inc('collect_count', 1)->update();
            } else {   // 取消收藏
                $result = self::where('id', '=', $goods_id)->dec('collect_count', 1)->update();
            }
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 修改售价
     * @param $id
     * @param $price
     */
    public static function modifyGoodsSalePrice($id, $price)
    {
        try {
            $result = self::where('id', '=', $id)->update(['sale_price' => $price]);
            return $result;
        }catch (Exception $e){
            throw $e;
        }
    }

    public static function modifyGoodsSaled($id){
        try {
            $result = self::where('id', '=', $id)->update(['status' => StatusEnum::SALED]);
            return $result;
        }catch (Exception $e){
            throw $e;
        }
    }

    /**
     * 获得正常商品
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws GoodsException
     */
    public static function getNormalGoods($id)
    {
            $result = self::where('id','=',$id)->where('status','=',StatusEnum::NORMAL)->find();
            if(!$result){
                throw new GoodsException();
            }
            else{
                return $result;
            }
    }

    public static function getCollectedGoods($page=1,$size=15)
    {
        $uid = Token::getCurrentUid();
        $collected = ScopeCollection::COLLECTED;
        // 获取收藏的商品
        // 建立子查询

        //select id,description,sale_price,original_price,main_img_url,postage_free,can_be_knife,contact_way,
        //contact_number,comment_count,collect_count,g.create_time,collected from goods as g INNER JOIN collection as c
        //on c.user_id=2 and c.collected=1 and g.id=c.goods_id;
        $result = Db::table('goods')->alias('g')
                        ->field('create_time','','goods','','')
                        ->field('id,description,sale_price,original_price,main_img_url,
                        postage_free,can_be_knife,contact_way,contact_number,
                        comment_count,collect_count,collected')
                        ->join('collection c',"c.user_id=$uid and c.collected=$collected and g.id=c.goods_id")
                        ->paginate($size,true,[
                            'page' => $page
                        ]);
        return $result;
    }
}