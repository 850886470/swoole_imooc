<?php

namespace app\common\lib\task;
/**
 * Task任务分发
 * Date: 2018/4/5
 * Time: 19:44
 */

use app\common\lib\ali\Sms;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;

class Task
{
    public function sendSms($data)
    {
        try{
            $res = Sms::sendSms($data['mobile'],$data['code']);
        } catch (\Exception $e) {
            echo $e->getMessage();
           return false;
        }

        if ($res == 'ReturnCode=1') {

            Predis::getInstance()->set(Redis::smsKey($data['mobile']),$data['code'],config('redis.expire_time'));

        } else {
            echo 'err1';
            return false;
        }

        echo 'err2';

        return true;
    }
}