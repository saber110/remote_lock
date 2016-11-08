<?php

require('Client.php');
require('FingerPrint.php');

$finger = new FingerPrint();
$identify = $finger->PS_Identify();

if($identify['status'] == '0') {
  echo "enene";
  $client = new Client('192.168.43.124', 9501);
  $client->connect(json_encode($identify));
}else{
  echo 'aaaaa';
}
