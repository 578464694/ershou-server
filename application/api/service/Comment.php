<?php
namespace app\api\service;


use app\api\model\Goods as GoodsModel;
use app\api\model\Comment as CommentModel;
use app\lib\exception\CommentException;
use app\lib\exception\GoodsException;

class Comment
{
    /**
     * 添加评论
     * @param $comment
     * @param $goodsId
     * @throws GoodsException
     */
    public static function addComment($content,$goodsId){
        $goods = GoodsModel::get($goodsId);
        if(!$goods){
            throw new GoodsException();
        }
        else{
            $userId = Token::getCurrentUid();
            $result = CommentModel::createComment($content,$goodsId,$userId);
            if($result){    //评论成功，增加评论次数
                $incResult = GoodsModel::incOneAtCommentCount($goodsId);
                return $incResult;
            }
            else{
                throw new CommentException();
            }
        }

    }
}