<?php 
require __DIR__ . "/WebSocketClient.php";
$host = '127.0.0.1';
$prot = 9502;

$client = new WebSocketClient($host, $prot);
$result = $client->connect();
if($result)
{
	$data = array('mid'=>'4','time'=>date("Y-m-d H:i:s",time()),'status'=>'0','name'=>'岳宏伟');
	$client->send(json_encode($data,JSON_UNESCAPED_UNICODE));
}
else
{
	echo "connect failed";
}

//$recvData = "";
//$tmp = $client->recv();