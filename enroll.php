<?php
require('FingerPrint.php');

$finger = new FingerPrint();

$enroll = $finger->PS_UpImage();

echo json_encode($enroll);
