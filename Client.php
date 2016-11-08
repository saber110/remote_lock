<?php

class Client
{
  private $client;
  private $host;
  private $port;
  private $timeout;

  public function __construct($host, $port , $timeout = 0.1) {
    $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
    $this->host = $host;
    $this->port = $port;
    $this->timeout = $timeout;
    $this->client->on("receive", function(swoole_client $cli, $data){
      if($data == 'success') {
        $cli->close();
      }
      else{
        echo "[" . date('Y-m-d') . "]: socket connection failed!\n";
      }
    });
    $this->client->on("error", function(swoole_client $cli){
        echo "[" . date('Y-m-d') . "]: " . "ERROR\n";
    });
    $this->client->on("close", function(swoole_client $cli){
        echo "[" . date('Y-m-d') . "]: " . "connection completed\n";
    });
  }

  public function connect($data) {
    $this->client->on("connect", function(swoole_client $cli) use ($data) {
        $cli->send("{$data}");
    });

    if (!$this->client->connect($this->host, $this->port, $this->timeout)) {
      echo "[" . date('Y-m-d') . "]: " . "Error: {$this->client->errMsg}[{$this->client->errCode}]\n";
    }
  }
}
