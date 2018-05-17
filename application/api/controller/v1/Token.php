<?php
namespace app\api\controller\v1;

use app\api\validate\GetToken;
use app\lib\exception\ParamException;
use app\api\service\UserToken;
use app\api\service\Token as TokenService;
use app\lib\message\SuccessMessage;
use think\Controller;
use think\Request;


class Token extends Controller
{
    //校验 token
//    1、获取 token
//    2、token 非空
//    3、从缓存中获取 token
//    4、存在，返回客户端 201
//    5、不存在，返回 false
    public function verifyToken()
    {
        $token = Request::instance()->header('token');
        if (!$token) {
            throw new ParamException([
                'msg' => 'token不能为空'
            ]);
        }
        $result = TokenService::verifyToken($token);
        return [
            'is_valid' => $result
        ];
    }

    /**
     * 获取 token
     * @param string $code
     */
    public function getToken($code = '')
    {
        (new GetToken())->goCheck();
        $user_token = new UserToken($code);
        $token = $user_token->getToken();
        return [
            'token' => $token
        ];
    }
}