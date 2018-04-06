<?php

namespace app\admin\controller;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/6
 * Time: 14:07
 */
use app\common\lib\Util;
class Image
{
    public function index()
    {
        $files = request()->file('file');
        $info = $files->move('/var/www/html/swoole_imooc/public/static/upload');

        if ($info) {
            $data = [
                'image'=>config('live.host').'/upload/'.$info->getSaveName(),
            ];
            return Util::show(config('code.success'),'ok',$data);
        } else {
            return Util::show(config('code.error'),'Upload failed');

        }
    }
}