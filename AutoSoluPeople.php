






<?php
header('Access-Control-Allow-Origin:*');       
header('Access-Control-Allow-Methods:POST');     
header('Access-Control-Allow-Headers:x-requested-with,content-type');  
header("Content-Type: text/html;charset=utf-8"); 
$con = mysql_connect("localhost","root","root");
if (!$con) { die('db connect error: ' . mysql_error()); }
mysql_select_db("bihu", $con);
$userId=isset($_POST['userId']) ? $_POST['userId'] : '';
$userName=isset($_POST['userName']) ? $_POST['userName'] : '';
$userIcon=isset($_POST['userIcon']) ? $_POST['userIcon'] : '';
$artNum=isset($_POST['artNum']) ? $_POST['artNum'] : '';
$fans=isset($_POST['fans']) ? $_POST['fans'] : '';
$follow=isset($_POST['follow']) ? $_POST['follow'] : '';
$follows=isset($_POST['follows']) ? $_POST['follows'] : '';
$info=isset($_POST['info']) ? $_POST['info'] : '';
if (empty($userId)){
	return false;
}
$sql_query_one="delete from people where bh_id='".$userId."'";
if (mysql_query($sql_query_one,$con))
{
  $array["status"]=200;
  $array["delete"]="ok";
}
else{
	$array["status"]=400;
  	$array["delete"]=die('Error: ' . mysql_error());
}

$sql_string="INSERT INTO people VALUES (default,'".$userId."','".$userName."','".$userIcon."','".$artNum."','".$fans."','".$follow."','".$follows."','".$info."','')";
if (mysql_query($sql_string,$con)) {
  
  $array["status"]=200;
  $array["add"]="ok";
 }
 else{
 	$array["status"]=400;
 	$array['add']=die('Error: ' . mysql_error());
 }
  
echo json_encode($array);



mysql_close($con);
?>

