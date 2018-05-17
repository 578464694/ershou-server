<?php
namespace app\api\model;
use think\Model;
use app\lib\enum\ScopeSetting;

class BaseModel extends Model
{
    protected function prefixUrl($url,$array){
        if(!$url){
            return '';
        }
        else{
            $url = ScopeSetting::IMG_URL_PREFIX.$url;
            return $url;
        }
    }

    protected function integerConvertToBoolean($value,$data)
    {
        if($value){
            return true;
        }
        else{
            return false;
        }
    }
}