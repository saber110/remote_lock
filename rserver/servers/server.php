<?php
// Server
require __DIR__ . "/WebSocketClient.php";
class Server
{
    private $serv;
    private $db;

    public function __construct() {
		//数据库连接
		//$this->db = new mysqli('localhost','pi','','ylg');
		$this->db = new mysqli('localhost','root','','ylg');
		if ($this->db->connect_error)
            die("连接失败: " . $this->db->connect_error);
    $this->db->query("set names 'utf8'");    
		//指纹数据接收server
        $this->serv = new swoole_server("0.0.0.0", 9501);
        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => false,
        ));
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));

        $this->serv->start();
    }

    public function onStart( $serv ) {

        echo "Start\n";
    }

    public function onConnect( $serv, $fd, $from_id ) {
        //$serv->send( $fd, "Hello {$fd}!" );
    }

    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        //echo "Get Message From Client {$fd}:{$data}\n";
        //$serv->send($fd, $data);
		$serv->send( $fd, "success" );
        $data = json_decode($data,1);
		var_dump($data);
        if($data['status']==0)
            $data=$this->insert_db($data);

		if(!$data)
		{
			echo "no people\n";
			return ;
		}
		var_dump($data);
		$host = '192.168.43.151';
		$prot = 9502;

		$client = new WebSocketClient($host, $prot);
		$result=$client->connect();
		if($result)
			$client->send(json_encode($data,JSON_UNESCAPED_UNICODE));
		else
			echo "connect failed";
    }
    public function insert_db($data)
    {
        //$data=array('status'=>0,'id'=>'4');
		//print_r($data);
		//$data['id']=4;
        $sql = "SELECT * FROM  `member` WHERE  `fingerprint` ={$data['id']}";
        $result = $this->db->query($sql);
        //var_dump($result) ;
		// return ;
        //echo '</br></br>';
        if($result)
        {
            if ($result->num_rows>0)
            {
                $row = $result->fetch_assoc();
				print_r($row);
                $insert_data = array('mid'=>$row['mid'],'time'=>date("Y-m-d H:i:s",time()),'status'=>'0','name'=>$row['name']);
                $sql = "INSERT INTO  `ylg`.`open_message` (`open_id` ,`mid` ,`time`,`status`)VALUES (NULL,'{$row['mid']}','{$insert_data['time']}' ,'{$insert_data['status']}')";
                //echo $sql;
                $result = $this->db->query($sql);
                //if($result)
                 return $insert_data;
                //else
                //    return 0;
            }
        }
		return  0;
    }

    public function onClose( $serv, $fd, $from_id ) {
        //echo "Client {$fd} close connection\n";
    }
}
// 启动服务器
$server = new Server();
