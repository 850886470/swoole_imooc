<?php
class Http
{
    const HOST = "0.0.0.0";
    const PORT = 8811;

    public $http = null;

    public function __construct()
    {

        $this->http =  new swoole_http_server(self::HOST,self::PORT);
        $this->http->set([
            'enable_static_handler' => true,
            'document_root' => "/var/www/html/swoole_imooc/public/static",
            'worker_num'=>5,
            'task_worker_num'=>4
        ]);
        $this->http->on('WorkerStart',[$this,'onWorkerStart']);
        $this->http->on('request',[$this,'onRequest']);
        $this->http->on('task',[$this,'onTask']);
        $this->http->on('finish',[$this,'onFinish']);
        $this->http->on('close',[$this,'onClose']);

        $this->http->start();
    }

    /**
     * @return swoole_http_server
     */
    public function onWorkerStart($server,$worker_id)
    {
        define('APP_PATH', __DIR__ . '/../application/');

        //加载框架文件
        require __DIR__ . '/../thinkphp/start.php';
    }

    public function onRequest($request,$response)
    {
        $_GET = [];
        $_POST = [];
        $_SERVER = [];

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

        $_POST['http_server'] = $this->http;

        try{
            think\Container::get('app', [defined('APP_PATH') ? APP_PATH : ''])
                ->run()
                ->send();
        }catch (\Exception $e) {
            echo $e->getMessage();
        }

        $res = ob_get_contents();
        ob_end_clean();

        $response->end($res);
    }


    public function onTask($server,$taskId,$workerId,$data) {

//        $sms = new app\common\lib\ali\Sms;
//        try{
//            $res = $sms::sendSms($data['mobile'],$data['code']);
//        } catch (\Exception $e) {
//           echo $e->getMessage();
//        }

        //分发task任务
        $obj = new app\common\lib\task\Task;

       if(isset($data['method'])) {
           $method = $data['method'];
           $flag = $obj->$method[$data['data']];


       }

        return "On task finish\n"; //通知worker
    }


    public function onFinish($serv,$taskId,$data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}";

    }



    public function onClose($serv,$fd) {
        echo "Client $fd closed\n";
    }
}

new Http();