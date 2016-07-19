<?php 
	require_once 'common.php';
	$uid = $_GET["uid"];
	$NewResetToken = cm_generateRandomString(10);
	
	$db     = cm_connect();	
	$query  = "UPDATE `Client` SET  `Client`.`ResetToken` = '$NewResetToken'  WHERE `Client`.`ClientID` = '$uid'";
	$result = mysqli_query($db, $query);
	cm_close_connect($db);
	
	

	echo '<h1>Đã hủy yêu cầu đổi password.</h1>';
?>