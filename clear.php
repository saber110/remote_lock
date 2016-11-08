<?php
require('FingerPrint.php');

$finger = new FingerPrint();

$clear = $finger->PS_Empty();

echo json_encode($clear);
