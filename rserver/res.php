<?php
function ajax_return($status,$message, $img='')
{
	$data = array('status'=>$status,'message'=>$message, 'img' => $img);
	return json_encode($data,JSON_UNESCAPED_UNICODE);
}
if(isset($_GET['mid'])&&$_GET['mid'])
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'http://192.168.43.151/wzr/enroll.php');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$result = curl_exec($ch);
	curl_close($ch);
	// echo $result;
	if(!json_decode($result, TRUE)['status']){
			echo ajax_return(0,"failed");
	}
	else
	{
		// var_dump($result);
		$data=json_decode($result,1);

		//echo '</br></br>';
		if($data['status']!= 0)
		{
			echo  ajax_return(0,"failed");
			return ;
		}
		//$connect = new mysqli('192.168.253.3','root','','ylg');
		$connect = new mysqli('localhost','root','','ylg');
		if ($connect->connect_error) {
        	die("连接失败: " . $connect->connect_error);
    }
    $sql = "UPDATE  `ylg`.`member` SET `fingerprint`='{$data['id']}',`fingerprint_pic`='{$data['img']}' WHERE  `member`.`mid` ={$_GET['mid']}";
		$results = $connect->query($sql);
    if ($results)
        echo  ajax_return(1,"success", base64_encode(pack('H*', $data['img'])));
    else
        echo ajax_return(0,"insert failed", $data['img']);
	}
}
else
	echo ajax_return(0,"get failed");

?>
