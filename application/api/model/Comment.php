<?php
namespace app\api\model;
use think\Model;

class Comment extends BaseModel
{
    protected $hidden = ['user_id','delete_time','school_name','status','update_time','floor'];
    public function user(){
        return $this->belongsTo('user','user_id','id');
    }
    /**
     * 创建一条评论记录
     * @param $content
     * @param $goodsId
     * @param $userId
     * @return false|int
     */
    public static function createComment($content,$goodsId,$userId){
        $data = [
            'content' => $content,
            'goods_id' => $goodsId,
            'user_id' => $userId
        ];
        $comment = new Comment();
        $result = $comment->save($data);
        return $result;
    }

    /**
     * 分页查询评论记录
     * @param int $page
     * @param int $size
     * @return \think\Paginator
     */
    public static function getCommentsByPaginate($goods_id, $page = 1, $size = 15){
        $comments = self::with('user')->where('goods_id','=',$goods_id)->order('create_time','asc')->paginate($size,true,[
            'page' => $page
        ]);
        return $comments;
    }
}