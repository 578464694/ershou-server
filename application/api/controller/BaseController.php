<?php
namespace app\api\controller;

use app\api\service\Token;
use think\Controller;

class BaseController extends Controller
{
    /**
     * 用户和管理员权限
     * @return bool
     */
    protected function checkPrimaryScope()
    {
        return Token::beforePrimaryScope();
    }

    /**
     * 用户权限
     * @return bool
     */
    protected function checkExclusiveScope()
    {
        return Token::beforeExclusiveScope();
    }

}