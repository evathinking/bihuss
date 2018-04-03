





<?php ob_start(); ?>




<?php

$con = mysql_connect("localhost","root","root");
if (!$con) { die('db connect error: ' . mysql_error()); }
mysql_select_db("bihu", $con);
$type=isset($_POST['type']) ? $_POST['type'] : '';
$array = array();
$array["type"]=$type;
if ($type=="article"){
$sql_query_one="select max(artid) from article";
$newid= mysql_fetch_array(mysql_query($sql_query_one,$con));
	if ($newid) {
		$array["status"]=200;
		$array["newid"]=$newid;
	}
	else {
		$array["status"]=400;
	  	$array["error"]=die('Error: ' . mysql_error());
	}

}

else if($type=="people"){
$sql_query_one="select max(bh_id) from people";
$newid= mysql_fetch_array(mysql_query($sql_query_one,$con));
	if ($newid) {
		$array["status"]=200;
		$array["newid"]=$newid;
	}
	else {
		$array["status"]=400;
	  	$array["error"]=die('Error: ' . mysql_error());
	}


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