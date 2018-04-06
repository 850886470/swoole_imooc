<?php
namespace app\index\controller;

use app\common\lib\Util;
use app\common\lib\Redis;
use app\common\lib\redis\Predis;
use think\Exception;

class Login
{
    public function index()
    {
        $mobile =  isset($_GET['phone_num']) ? intval($_GET['phone_num']) : '';
        $code = isset($_GET['code']) ? intval($_GET['code']): '';

        if (!$mobile || !$code)
            return Util::show(config('code.error'),'mobile or code is empty');

        try{
            $redisCode = Predis::getInstance()->get(Redis::smsKey($mobile));

        }catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }

        if ($redisCode == $code) {

            $data = [
                'user'=>$mobile,
                'srcKey'=>md5(Redis::userKey($mobile)),
                'time'=>time(),
                'isLogin'=>true,
            ];
            Predis::getInstance()->set(Redis::userKey($mobile),$data);

            return Util::show(config('code.success'),'ok',$data);
        } else {
            return Util::show(config('code.error'),'login error');

        }

    }


}
