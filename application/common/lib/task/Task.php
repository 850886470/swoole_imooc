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
    public function sendSms($data,$server)
    {
        try{
            $res = Sms::sendSms($data['phone_num'],$data['code']);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }


        if ($res == 'ReturnCode=1') {

            Predis::getInstance()->set(Redis::smsKey($data['phone_num']),$data['code'],config('redis.expire_time'));

        } else {

            return false;
        }


        return true;
    }

    public function pushLive($data,$server)
    {
        $clients = Predis::getInstance()->sMembers(config('redis.live_game_key'));

        foreach ($clients as $fd)
        {
            $_POST['http_server']->push($fd,json_encode($data));
        }

    }
}