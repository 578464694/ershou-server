<?php
namespace app\lib\enum;

class ScopeWx
{
    const APP_ID = 'wxf16257b7d5a16620';
    const SECRET = 'f0d3a75642850c1db2ed539b19cca1cb';
    const WX_BASE_URL = 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';
}
?>
