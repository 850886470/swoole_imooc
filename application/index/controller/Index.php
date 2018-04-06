<?php
namespace app\index\controller;

use app\common\lib\ali\Sms;

class Index
{
    public function index()
    {
        return '';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
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
