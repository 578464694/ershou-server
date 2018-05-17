<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\lib\exception\SaveImageException;
use app\lib\message\SaveImageSuccess;
use think\Request;

class UploadImage extends BaseController
{
//    protected $beforeActionList = [
//        'checkPrimaryScope' => ['only' => 'upload']
//    ];

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $info = $file->move('images');
        //　双传成功
        if($info && $info->getPathname()) {
            $pathname = $info->getPathname();
            $host = $_SERVER['HTTP_HOST'];
            $pathname = $this->convertBackslashToSlash($pathname);
            $url = 'https://' . $host . '/' . $pathname;

            return json(new SaveImageSuccess($url),201);
        }
        else{
            throw new SaveImageException();
        }
    }

    /**
     * 左斜线转右斜线
     */
    protected function convertBackslashToSlash($path)
    {
        return preg_replace('/\\\\/', '/', $path);
    }


}