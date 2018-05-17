<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17
 * Time: 10:26
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\enum\ScopeSetting;
use app\lib\enum\ScopeSecure;
use app\lib\exception\ParamException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Log;
use think\Request;

class Token
{

    protected function generateToken()
    {
        $randChars = getRandChars(32);
        $request_time = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = ScopeSecure::SALT;
        return md5($randChars . $request_time . $salt);
    }

    /**
     * 保存 cacheValue 到缓存
     * @param $cacheValue
     * @return string
     * @throws TokenException
     */
    protected function saveValueToCache($cacheValue)
    {
        $token = $this->generateToken();
        $expire_in = ScopeSetting::IMG_URL_PREFIX;
        $cacheValue = json_encode($cacheValue);
        $result = cache($token, $cacheValue, $expire_in);
        if (!$result) {
            throw new TokenException([
                'msg' => '服务器缓存异常，token获取失败',
                'errorCode' => 10005
            ]);
        }
        return $token;
    }

    /**
     * 根据 token 获取缓存
     * @param $token
     * @return mixed
     * @throws TokenException
     */
    public static function verifyToken($token)
    {
        $result = Cache::get($token);
        if($result) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 根据 key，token 获取 cache 中值
     * @param $key
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $cache = Cache::get($token);
        if(!$cache){
            throw new TokenException();
        }
        else{
            $cache = json_decode($cache,true);
            if(!is_array($cache)){
                json_decode($cache,true);
            }
            if(array_key_exists($key, $cache)){
                return $cache[$key];
            }
            else{
                throw new Exception('token的key不存在');
            }
        }
    }

    /**
     * 获得当前请求的 uid
     * @return mixed
     */
    public static function getCurrentUid()
    {
        return self::getCurrentTokenVar('uid');
    }

    public static function getCurrentScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        return $scope;
    }

    /**
     * 校验用户和管理员权限
     */
    public static function beforePrimaryScope()
    {
        $scope = self::getCurrentScope();
        if($scope >= ScopeEnum::USER){
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 用户能访问的权限
     */
    public static function beforeExclusiveScope()
    {
        $scope = self::getCurrentScope();
        if($scope == ScopeEnum::USER){
            return true;
        }
        else {
            return false;
        }
    }


}