<?php 

	require_once 'common.php';
	require_once('PHPMailer/PHPMailerAutoload.php');
	

	$uid = $_GET["uid"];
	$ResetToken = $_GET["resettoken"];
	$NewPassword =  cm_generateRandomString(6);
	$pass_hash = password_hash($NewPassword, PASSWORD_DEFAULT);
	$NewResetToken = cm_generateRandomString(10);

	
	
	$db     = cm_connect();	
	$query  = "SELECT * FROM `Client` WHERE `Client`.`ClientID` = '$uid' AND ResetToken='$ResetToken'";
	
	$result = mysqli_query($db, $query);
	$data   = array();  
	while ($row = mysqli_fetch_array($result)) {
		array_push($data, $row);
	}
		
	if(count($data)==0)
	{
		echo '<h1>Link đổi password đã hết hạn.<h/1>';
		exit(0);
	}

	$query  = "UPDATE `Client` SET `Client`.`ContactPassword` = '$pass_hash', `Client`.`ResetToken` = '$NewResetToken'  WHERE `Client`.`ClientID` = '$uid'  ";
	$result = mysqli_query($db, $query);
	cm_close_connect($db);

	
	//send email to customer
	$to = array($data[0]['ContactEmail']);
	$subject = "Password mới cho kiemtraduan.net";
	$content = cm_get_send_new_pass_mail_template();
	$content = str_replace("[%clientname%]",  $data[0]['ClientName'], $content );
	$content = str_replace("[%password%]", $NewPassword, $content );	
	
	

	//echo $content;
	$return_data = cm_send_mail($to,$subject,$content); 
	$json_obj =  json_decode($return_data);
	if($json_obj->code == 200)
	{
		echo "<h1>Password mới đã được gởi cho bạn.</h1>";
	}
	else
	{
		echo "<h1>Tạm thời không thể gởi email.</h1>";
	}


?>