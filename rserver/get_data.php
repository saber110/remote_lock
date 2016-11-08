<?php
class get_data
{
    private $db;

    public function __construct() {
		//数据库连接
		$this->db = new mysqli('localhost','root','','ylg');
		if ($this->db->connect_error)
            die("连接失败: " . $this->db->connect_error);
    $this->db->query("set names 'utf8'");
    }

    public function get_member() {
    	$sql = "select mid,name from member";
    	$result = $this->db->query($sql);
    	$return_data=array();
    	while($row=mysqli_fetch_assoc($result)){
    		$return_data[]=$row;
    	}
    	echo json_encode($return_data,JSON_UNESCAPED_UNICODE);
    }

    public function get_open_message() {
    	$sql = "select * from  open_message,member where open_message.mid=member.mid";
    	$result = $this->db->query($sql);
    	$return_data=array();
    	while($row=mysqli_fetch_assoc($result)){
    		$return_data[]=$row;
    	}
    	echo json_encode($return_data,JSON_UNESCAPED_UNICODE);
    }

}
$get_data = new get_data();
if(isset($_GET['status']))
{
	if($_GET['status']==1)
		$get_data->get_member();
	else if($_GET['status']==2)
		$get_data->get_open_message();
}
