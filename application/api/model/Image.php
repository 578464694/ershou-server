<?php
namespace app\api\model;

use app\api\service\Token;
use think\Exception;

class Image extends BaseModel
{
    protected $hidden = ['id','create_time','update_time','delete_time','from'];
    public function img()
    {
        return $this->hasOne('GoodsImage');
    }

    public function getUrlAttr($url,$data){
        return $this->prefixUrl($url,$data);
    }

    /**\
     * 保存图片
     * @param $images
     * @return array|false
     */
    public static function saveImages($images_url,$goods_id)
    {
        $uid = Token::getCurrentUid();
        $image = new self();
        $imagesUrl = self::formatImagesUrl($images_url);
        $result = $image->saveAll($imagesUrl);
        foreach ($result as $obj){
            $save = $obj->img()->save(['goods_id' => $goods_id, 'user_id' => $uid]);
            if(!$save){
                echo 'nimei';
            }

        }
        return $result;
    }

    public static function saveImageAndGoodsImage($url, $goods_id)
    {
        $uid = Token::getCurrentUid();
        try {
            $image = new self();
            $image->save(['url' => $url]);
            $result = $image->img()->save(['goods_id' => $goods_id, 'user_id' => $url]);
            return $result;
        } catch (Exception $e){
            throw $e;
        }
    }

    /**
     * 将 url 数组格式化成,形如
     * [
     *      ['url' => 'xx.jpg']
     * ]
     * 的二维数组
     */
    public static function formatImagesUrl($urls){
        $imagesUrl = [];
        for($i = 0;$i < sizeof($urls);$i++){
            $url = ['url' => $urls[$i]];
            array_push($imagesUrl,$url);
        }
        return $imagesUrl;
    }
}