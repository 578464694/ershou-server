<?php
namespace app\api\model;

use think\Model;

class GoodsImage extends Model
{
    protected $hidden = ['id','image_id','user_id','goods_id','create_time','update_time'];
    /**
     * 关联 image 表
     */
    public function imgUrl()
    {
        return $this->belongsTo('Image','image_id','id');
    }

    /**
     * 关联新增 image 表
     */
    public static function relatedSave($url)
    {
        $goodsImage = new self();
        $result = $goodsImage->img()->save($url);
        return $result;
    }

}