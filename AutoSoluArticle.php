





<?php ob_start(); ?>




<?php

$con = mysql_connect("localhost","root","root");
if (!$con) { die('db connect error: ' . mysql_error()); }
mysql_select_db("bihu", $con);
$code=isset($_POST['code']) ? $_POST['code'] : '';
$artId=isset($_POST['artId']) ? $_POST['artId'] : '';
$userId= isset($_POST['userId']) ? $_POST['userId'] : '';
$userName=isset($_POST['userName']) ? $_POST['userName'] : '';
$title=isset($_POST['title']) ? $_POST['title'] : '';
$cmts= isset($_POST['cmts']) ? $_POST['cmts'] : '';
$creatime=isset($_POST['creatime']) ? $_POST['creatime'] : '';
$content=isset($_POST['content']) ? json_decode($_POST['content']) : '';
echo $content;
$snapContent=isset($_POST['snapContent']) ? $_POST['snapContent'] : '';
$money=isset($_POST['money']) ? $_POST['money'] : '';
$ups=isset($_POST['ups']) ? $_POST['ups'] :'';
$downs=isset($_POST['downs']) ? $_POST['downs'] : '';
if (empty($artId)){
	return false;
}
$array = array();
$sql_query_one="delete from article where artId='".$artId."'";
if (mysql_query($sql_query_one,$con))
{
  $array["status"]=200;
  $array["delete"]="ok";
}
else{
	$array["status"]=400;
  	$array["delete"]=die('Error: ' . mysql_error());
}

$sql_string="INSERT INTO article VALUES (default,'".$code."','".$artId."','".$userId."','".$userName."','".$title."','".$cmts."','".$creatime."','".$content."','".$snapContent."','".$money."','".$ups."','".$downs."')";

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

<?php

header('Access-Control-Allow-Origin:*');       
header('Access-Control-Allow-Methods:POST');     
header('Access-Control-Allow-Headers:x-requested-with,content-type');  
header("Content-Type: text/html;charset=gb2312");
ob_end_flush();
?>