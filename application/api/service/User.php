<?php
namespace app\api\service;

use app\api\model\User as UserModel;
use app\lib\exception\UserException;

class User
{
    /**
     * 设置或更新用户信息
     * @param $userInfo
     * @return false|int
     * @throws UserException
     */
    public static function setUserInfo($userInfo)
    {
        $uid = Token::getCurrentUid();
        if (self::isUserInfoDifferent($uid, $userInfo)) {
            UserModel::saveUserInfo($uid, $userInfo);
        }
    }

    /**
     * 校验用户信息是否发生改变
     * 如果改变则修改 user
     * &$user 用户信息的数组
     * $userInfo 接收的用户信息
     * @return bool
     */
    protected static function isUserInfoDifferent($uid, $userInfo)
    {
        $change = false;
        $user = UserModel::getOneUserArray($uid);
        foreach ($user as $key => $value) {
            if(array_key_exists($key,$userInfo)){
                if ($userInfo[$key] !=  $user[$key]) {
                    $change = true;
                    break;
                }
            }
        }
        if($change){    //如果被修改
            return $userInfo;
        }
        else{
            return false;
        }
    }
}