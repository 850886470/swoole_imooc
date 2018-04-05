<?php
/**
 * Created by PhpStorm.
 * User: baidu
 * Date: 18/2/28
 * Time: 上午1:39
 */
$http = new swoole_http_server("0.0.0.0", 80);

$http->set(
    [
        'enable_static_handler' => true,
        'document_root' => "/var/www/html/swoole_imooc/public/static",
        'worker_num'=>5
    ]
);

$http->on('WorkerStart', function(swoole_server $server,$worker_id) {

    define('APP_PATH', __DIR__ . '/../application/');

    //加载框架文件
    require __DIR__ . '/../thinkphp/base.php';
});

$http->on('request', function($request, $response) use ($http) {
    if(isset($request->server)) {
        foreach ($request->server as $k=>$v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }

    if(isset($request->header)) {
        foreach ($request->header as $k=>$v) {
            $_SERVER[strtoupper($k)] = $v;
        }
    }

    if(isset($request->get)) {
        foreach ($request->get as $k=>$v) {
            $_GET[$k] = $v;
        }
    }

    if(isset($request->post)) {
        foreach ($request->post as $k=>$v) {
            $_POST[$k] = $v;
        }
    }

    ob_start();

    try{
        think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
            ->run()
            ->send();
    }catch (Exception $e) {

    }



    $res = ob_get_contents();
    ob_clean();

    $response->end($res);
    $http->close();
});

$http->start();