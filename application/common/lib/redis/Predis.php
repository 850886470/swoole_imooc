<?php

namespace app\common\lib\redis;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/5
 * Time: 18:52
 */
class Predis
{

    public $redis = '';

    private static $instance = NULL;

    private function __construct()
    {
        $this->redis = new \Redis();
        $res = $this->redis->connect(
            config('redis.host'),
            config('redis.port'),
            config('redis.timeout')

            );

        if (!$res) {
            throw new \Exception('redis connect error.');
        }
    }

    public static function getInstance()
    {
        if (self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set($key,$value,$time = 0)
    {
        if (!$key)
            return false;

        if (is_array($value))
            $value = json_encode($value);

        if (!$time)
            return $this->redis->set($key,$value);

        return $this->redis->set($key,$value,$time);
    }

    public function get($key)
    {
        if (!$key)
            return false;

        return $this->redis->get($key);
    }
}