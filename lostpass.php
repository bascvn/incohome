<?php
// do php stuff
include('template/header.php');
?>

<?php 

	$db     = cm_connect();
	
	$ContactEmail = '';
	if(isset($_GET["username"])){
		$ContactEmail = $_GET["username"];  
		$ContactEmail  = mysqli_real_escape_string($db,$ContactEmail);	
	}else{
		Echo "<p>Vui lòng nhập Username(là email).</p>";
	}
	
	$ClientCode = '';
	if(isset($_GET["clientcode"])){
		$ClientCode = $_GET["clientcode"];  
		$ClientCode  = mysqli_real_escape_string($db,$ClientCode);	
	}
	else{
		Echo "<p>Vui lòng nhập mã công ty.</p>";
	}
	
	
	if(strlen($ContactEmail)>0 && strlen($ClientCode)>0){
		
		$query  = "SELECT * FROM `Client` WHERE `Client`.`ContactEmail` = '$ContactEmail' AND ClientCode='$ClientCode' ";
		$result = mysqli_query($db, $query);
		$data   = array();  
		
		while ($row = mysqli_fetch_array($result)) {
			array_push($data, $row);
		}
		
		
		if(count($data)!=1)
		{
			 cm_close_connect($db);
			//echo '{"code":404, "message":"Your email was not found in the system"}';
			echo "Không tìm thấy email của bạn trong hệ thống";
			
		}else{
			$to = array($ContactEmail);
			$subject = "Đổi password trên kiemtraduan.net";
			$content = cm_get_forgot_password_mail_template();
			$content = str_replace("[%clientname%]",  $data[0]['ClientName'], $content );
			$server_uri = cm_get_server_uri();
			

			$resettoken = cm_generateRandomString(10);
			$reset_link = $server_uri."resetpass.php?uid=". $data[0]['ClientID']."&resettoken=$resettoken";
			$content = str_replace("[%reset_password_link%]", $reset_link, $content );	
			$clear_reset_link = $server_uri."clear_resetpass.php?uid=". $data[0]['ClientID'];
			$content = str_replace("[%clear_reset_password_link%]", $clear_reset_link, $content );	


			//update reset token
			$query  = "UPDATE  `Client` SET `Client`.`ResetToken` = '$resettoken' WHERE `Client`.`ClientID` = '".$data[0]['ClientID']."'";
			$result = mysqli_query($db, $query);
			
			 cm_close_connect($db);
			$return_data = cm_send_mail($to,$subject,$content);
			
			echo "Đã gởi email đổi password cho bạn.";
		}

	}else{
		cm_close_connect($db);
	}
	
    
?>


<?php
// do php stuff
include('template/footer.php');
?>
