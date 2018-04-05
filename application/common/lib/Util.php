<?php
namespace app\common\lib;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/5
 * Time: 14:42
 */
class Util
{
    public static function request($url,$data = []) {
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        if ($data) {
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }

        $res = curl_exec($ch);

        curl_close($ch);

        return $res;

    }

    public static function show($status,$message = '',$data = '')
    {
        $result = [
            'status'=>$status,
            'message'=>$message,
            'data'=>$data,
        ];

        echo json_encode($result);

    }
}