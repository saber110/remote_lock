<?php

class Client
{
  private $client;

  public function __construct() {
    $this->client = new swoole_client(SWOOLE_SOCK_TCP);
  }

  public function connect() {
    if (!$this->client->connect("127.0.0.1", 9501 , 1)) {
      echo "Error: {$this->client->errMsg}[{$this->client->errCode}]\n";
    }

    fwrite(STDOUT, "Please enter your message:");
    $msg = trim(fgets(STDIN));
    $this->client->send($msg);

    $message = $this->client->recv();
    echo "Get Message From Server: {$message}\n";
  }
}
