<?php
namespace app\api\model;

use app\lib\exception\UserException;
use think\Model;

class User extends Model
{
    protected $hidden = [
            'id','openid','extend','school_id','country','province','city',
            'create_time','update_time','delete_time','status'
    ];

    public static function getByOpenId($openid)
    {
        $result = self::where('openid', '=', $openid)->find();
        return $result;
    }

    public static function createUser($openid) {
        $user = new self();
        $user->save(['openid' => $openid]);
        return $user->id;
    }

    public static function saveUserInfo($uid,$userInfo){
        $user = self::get($uid);
        $result = $user->save($userInfo);
        return $result;
    }

    public static function getOneUserArray($uid){
       $user = self::where('id','=',$uid)->find()->toArray();
       if(!$user){
           throw new UserException();
       }
       return $user;
    }

}