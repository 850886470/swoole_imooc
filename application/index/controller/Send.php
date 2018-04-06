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
