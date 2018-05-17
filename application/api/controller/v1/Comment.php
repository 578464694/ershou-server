<?php
namespace app\api\controller\v1;
use app\api\controller\BaseController;
use app\api\validate\AddCommentValidate;
use app\api\service\Comment as CommentService;
use app\api\validate\PaginateValidate;
use app\lib\exception\CommentException;
use app\lib\message\SuccessMessage;
use app\api\model\Comment as CommentModel;
use think\Exception;


class Comment extends BaseController
{
    public $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'addComment']
    ];

    /**
     * 添加评论
     * @return \think\response\Json
     * @throws Exception
     * TODO:: 待重构
     */
    public function addComment(){
        $commentValidate = new AddCommentValidate();
        $commentValidate->goCheck();
        $param = $commentValidate->getParameterByRule(input('post.'));
        try{
            $result = CommentService::addComment($param['content'],$param['goods_id']);
            if($result){
                return json(new SuccessMessage(['msg' => '评论成功']),201);
            }else{
                throw new CommentException(['msg' => '评论次数增加失败']);
            }
        }catch (Exception $e){
            throw $e;
        }
    }

    /**
     * 分页获得评论
     * @param int $page
     * @param int $size
     * @return \think\Paginator
     * @throws CommentException
     */
    public function getCommentsByPaginate($goods_id,$page = 1,$size = 15){
        (new PaginateValidate())->goCheck('comments');
        $comments = CommentModel::getCommentsByPaginate($goods_id,$page,$size);
        if(!$comments){
            throw new CommentException([
                'code' => 404,
                'msg' => '评论不存在',
                'errorCode' => 50001
            ]);
        }
        else{
            return $comments;
        }
    }
}