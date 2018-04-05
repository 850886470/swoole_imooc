<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;
use app\common\lib\Util;
use app\common\lib\Redis;

class Send
{

    public function index()
    {

        $mobile = request()->get('phone_num',0,'intval');

        if (!$mobile || !preg_match('/^1\d{10}$/',$mobile)) {
            return Util::show(config('code.error'),'error');
        }

        $code = rand(1000,9999);

        $taskData = [
            'method'=>'sendSms',
            'data'=>[
                'phone'=>$mobile,
                'code'=>$code,
            ]

        ];
        $_POST['http_server']->task($taskData);

        return Util::show(config('code.success'),'ok');


//        try{
//            $res = Sms::sendSms($mobile,$code);
//            echo 'Success';
//        } catch (\Exception $e) {
//            return Util::show(config('code.error'),$e->getMessage());
//        }

        if ($res == 'ReturnCode=1') {

//            $redis = new \Swoole\Coroutine\Redis();
//            $redis->connect(config('redis.host'),config('redis.port'));
//            $redis->set(Redis::smsKey($mobile),$code,config('redis.expire_time'));
//

        } else {
            return Util::show(config('code.error'),'验证码发送失败');
        }

    }

    public function sms()
    {

        try{
            $res = Sms::sendSms('13357169619',1234);
            echo 'Success';
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
