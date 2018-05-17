<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\UserInfoValidate;
use app\api\service\User as UserService;

class User extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'setUser']
    ];

    /**
     * 参数校验
     * 获取客户端参数
     * 获取用户id
     * 获取数据表中用户信息
     * 比较数据是否相同
     */
    public function setUser(){
        $userValidate = new UserInfoValidate();
        $userValidate->goCheck();
        $params = $userValidate->getParameterByRule(input('post.'));
        UserService::setUserInfo($params);
        return true;
    }
}