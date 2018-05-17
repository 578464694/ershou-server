<?php
namespace app\api\validate;

use app\lib\exception\ParamException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck($scene = '')
    {
        $request = Request::instance();
        $params = $request->param();
        if(!$scene){
            $result = $this->batch()->check($params);
        }
        else{
            $result = $this->batch()->scene($scene)->check($params);
        }
        if(!$result){
            $e = new ParamException([
                'msg' => $this->error
            ]);
            throw $e;
        }else{
            return true;
        }
    }

    public function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if(empty($value)) {
            return false;
        }
        else{
            return true;
        }
    }

    public function isPositiveFloat($number)
    {
        if( is_numeric($number) && is_float($number + 0.0) && ($number+0)>0 ) {
            return true;
        }
        else{
            return false;
        }
    }

    public function isPositiveInteger($number)
    {
        if( is_numeric($number) && is_integer($number + 0) && ($number+0>0) ) {
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 根据 rule 获取客户端传递的参数
     */
    public function getParameterByRule($params){
        if(array_key_exists('uid',$params) || array_key_exists('user_id',$params)){
            throw new ParamException([
                'msg' => '参数不能包含 uid 或 user_id'
            ]);
        }
        $newArray = [];
        try{
            foreach ($this->rule as $key => $value){
                $newArray[$key] = $params[$key];
            }
            return $newArray;
        }catch (ParamException $e){
            $e->setMsg('参数不完整');
            throw $e;
        }
    }
}