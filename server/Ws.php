<?php
class Ws
{
    const HOST = "0.0.0.0";
    const PORT = 8811;

    public $ws = null;

    public function __construct()
    {

        $this->ws =  new swoole_websocket_server(self::HOST,self::PORT);
        $this->ws->set([
            'enable_static_handler' => true,
            'document_root' => "/var/www/html/swoole_imooc/public/static",
            'worker_num'=>5,
            'task_worker_num'=>4
        ]);
        $this->ws->on('WorkerStart',[$this,'onWorkerStart']);
        $this->ws->on('request',[$this,'onRequest']);
        $this->ws->on('open',[$this,'onOpen']);
        $this->ws->on('message',[$this,'onMessage']);
        $this->ws->on('task',[$this,'onTask']);
        $this->ws->on('finish',[$this,'onFinish']);
        $this->ws->on('close',[$this,'onClose']);

        $this->ws->start();
    }

    /**
     * @return swoole_ws_server
     */
    public function onWorkerStart($server,$worker_id)
    {
        define('APP_PATH', __DIR__ . '/../application/');

        //加载框架文件
        require __DIR__ . '/../thinkphp/start.php';
    }



    public function onMessage($ws,$frame)
    {
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

        $ws->push($frame->fd,'Push'.date('Y-m-d H:i:s'));
    }

    public function onRequest($request,$response)
    {
        $_GET = [];
        $_POST = [];
        $_FILES = [];
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

        if(isset($request->files)) {
            foreach ($request->files as $k=>$v) {
                $_FILES[$k] = $v;
            }
        }

        if(isset($request->post)) {
            foreach ($request->post as $k=>$v) {
                $_POST[$k] = $v;
            }
        }

        ob_start();

        $_POST['ws_server'] = $this->ws;

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

        //分发task任务
        $obj = new app\common\lib\task\Task;

       if(isset($data['method'])) {
           $method = $data['method'];
           $flag = $obj->$method($data['data'],$server);
       }

        return "On task finish\n"; //通知worker
    }


    public function onFinish($serv,$taskId,$data) {
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}";

    }

    public function  onOpen($serv,$request) {
        app\common\lib\redis\Predis::getInstance()->sAdd(
            config('redis.live_game_key'),$request->fd
        );
        print_r($request->fd);

    }

    public function onClose($serv,$fd) {

        app\common\lib\redis\Predis::getInstance()->sRem(
            config('redis.live_game_key'),$fd
        );

        echo "Client $fd closed\n";
    }
}

new Ws();