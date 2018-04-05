<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/5
 * Time: 16:43
 */

namespace app\common\lib;


class Redis
{
    /**
     * 验证码前缀
     * @var string
     */
    private static $smsPre = 'sms_';
    private static $userPre = 'user_';

    public static function smsKey($mobile)
    {
        return self::$smsPre.$mobile;
    }

    public static function userKey($mobile)
    {
        return self::$userPre.$mobile;
    }
}