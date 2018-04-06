<?php

namespace app\admin\controller;
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/4/6
 * Time: 14:07
 */
use app\common\lib\Util;
use app\common\lib\redis\Predis;
class Live
{
    public function push()
    {
        if (empty($_GET)) {
            return Util::show(config('code.error'),'No message');
        }

        $teams = [
            1=>[
                'name'=>'马刺',
                'logo'=>'/live/imgs/team1.png'
            ],
           4 =>[
                'name'=>'火箭',
                'logo'=>'/live/imgs/team2.png'
            ]
        ];

        $data = [
            'type' => intval($_GET['type']),
            'title'=>isset($teams[$_GET['team_id']]['name'])?
                $teams[$_GET['team_id']]['name']:'直播员',
            'logo'=>isset($teams[$_GET['team_id']]['logo'])?
                $teams[$_GET['team_id']]['logo']:'',
            'content'=>isset($_GET['content'])?
                $_GET['content']:'',
            'image'=>isset($_GET['image']) ?$_GET['image']:''
        ];

        $taskData = [
          'method'=>'livePush',
            'data'=>$data
        ];
        //获取里连接的用户
        //赛况信息入库  组织好 push到页面

       $_POST['http_server']->task($taskData);



    }
}