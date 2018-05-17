<?php
namespace app\api\service;


use app\api\model\User as UserModel;
use app\lib\enum\ScopeEnum;
use app\lib\enum\ScopeWx;
use app\lib\exception\WeChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code = '';
    protected $wxAppID = '';
    protected $wxAppSecret = '';
    protected $wxLoginUrl = '';

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = ScopeWx::APP_ID;
        $this->wxAppSecret = ScopeWx::SECRET;
        $this->baseUrl = ScopeWx::WX_BASE_URL;
        $this->wxLoginUrl = sprintf($this->baseUrl, $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    //    获取 token
//    1、根据 code码，向微信服务器获取 session_key，open_id
//    2、没有结果 异常处理
//    3、有结果 判断是否存在 errorcode，存在 异常处理
//    4、 根据 open_id 查询用户表，没有结果添加记录，
//    5、将查询出来的结果保存到缓存中，键是 token
//    6、token 返回客户端
    public function getToken()
    {
        $wxResult = json_decode(curl_get($this->wxLoginUrl), true);
        if (!$wxResult) {
            throw new Exception('获取 session key 及 code码错误，微信服务器内部异常');
        } else {
            $isError = array_key_exists('errorcode', $wxResult);
            if ($isError) {
                $this->wxLoginError($wxResult);
            } else {
                return $this->grantToken($wxResult);
            }
        }
    }

    /**
     * 异常处理
     * @param $wxResult
     * @throws WeChatException
     */
    private function wxLoginError($wxResult)
    {
        throw new WeChatException([
            'errorCode' => $wxResult['errorcode'],
            'msg' => $wxResult['errormsg']
        ]);
    }

    /**
     * 生成 token
     * 1、查询数据库，是否存在用户
     * 2、不存在，生成一条数据
     * 3、整理数据
     * 4、生成 token
     * 5、保存到缓存
     * @param $wxResult
     * @throws \Exception
     */
    private function grantToken($wxResult)
    {
       # $openid = $wxResult['openid'];
        $openid = "ozHrv0AZs8VEZkKOcC1WsY5fD5so";
	$user = UserModel::getByOpenId($openid);
        $uid = 0;
        try {
            if (!$user) {
                $uid = UserModel::createUser($openid);
            }
            else {
                $uid = $user->id;
            }
            $cacheValue = $this->prepareCacheValue($wxResult, $uid);
            $token = $this->saveValueToCache($cacheValue);
            return $token;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 准备缓存数据
     * @param $wxResult
     * @param $uid
     * @return mixed
     */
    protected function prepareCacheValue($wxResult, $uid)
    {
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = ScopeEnum::USER;
        return $cacheValue;
    }

}
