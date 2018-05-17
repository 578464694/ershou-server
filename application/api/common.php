<?php
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //不做证书校验，linux环境下改为 true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

/**
 * 生成随机字符串
 * @param $length
 * @return string
 */
function getRandChars($length)
{
    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefihijklmnopqrstuvwxyz';
    $str_length = strlen($string);
    $rand_chars = '';
    for ($i = 0; $i < $str_length; $i++) {
        $rand_chars .= substr($string, rand(0, $str_length), 1);
    }
    return $rand_chars;
}