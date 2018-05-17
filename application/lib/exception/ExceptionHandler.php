<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/17
 * Time: 9:53
 */

namespace app\lib\exception;

use think\Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code = 400;
    private $msg = '';
    private $errorCode = 10000;

    /**
     * 异常处理层
     * @param \Exception $e
     * @return \think\Response|\think\response\Json
     */
    public function render(\Exception $e)
    {
        if($e instanceof BaseException){
            $this->code = $e->getHttpCode();
            $this->msg = $e->getMsg();
            $this->errorCode = $e->getErrorCode();
        }
        else{
            if(config('app_debug')){
                return parent::render($e);
            }
            else{
                $this->code = 500;
                $this->msg = '服务器内部错误，我不想告诉你'.$e->getMessage();
                $this->error_code = 999;
                $this->recordErrorLog($e);
            }
        }
        $url = Request::instance()->url();
        $result = [
            'code' => $this->code,
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' =>  $url
        ];
        return json($result,$this->code);
    }

    /**
     * 记录日志
     * @param \Exception $e
     */
    private function recordErrorLog(\Exception $e)
    {
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}