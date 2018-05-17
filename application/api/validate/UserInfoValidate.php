<?php
namespace app\api\validate;

class UserInfoValidate extends BaseValidate
{
    protected $rule = [
        'nick_name' => 'require',
        'gender' => 'require|in:0,1,2',
        'avatar_url' => 'require',
        'city' => 'chsAlpha',
        'province' => 'chsAlpha',
        'country' => 'chsAlpha',
    ];

}
