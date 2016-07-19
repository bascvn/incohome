<?php 

	require_once('PHPMailer/PHPMailerAutoload.php');
	
	 define("PRE_LENGTH",      2); 
	 define("POST_LENGTH",     3);
	 define("ASCII_DELTA",     9); 	
	 
	 define("SRV_PROTOCOL", "http://"); // this is used for DEV
	 define("API_PATH", "inco/"); // this is used for DEV
	 
	
	//======================================================================================
    function cm_get_server_uri(){
		$actual_link = SRV_PROTOCOL."$_SERVER[HTTP_HOST]/".API_PATH;
		return $actual_link;
	}
	
	//======================================================================================
    function cm_connect(){
		
		// Create connection
		 $con=mysqli_connect('127.0.0.1','root','12345','IncoClient');


		// Check connection
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		// Change character set to utf8
		mysqli_set_charset($con,"utf8");
		return $con;
    }

	//======================================================================================
	function cm_generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

    //======================================================================================
    function cm_close_connect($con){
        mysqli_close($con);
    }
    
    //======================================================================================
    function cm_send_mail($toArr,$subject,$content){
		
		$mail = new PHPMailer(); // create a new object
		$mail->CharSet = 'UTF-8';
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "basc.noreply@gmail.com";
			$mail->Password = "loveviet2007";
			$mail->SetFrom("basc.noreply@gmail.com");

		$mail->Subject = $subject;
		$mail->Body = $content;
		
		foreach($toArr as $to) {
			$mail->AddAddress($to);

		}

		

		 if(!$mail->Send())
		{
			//echo "Mailer Error: " . $mail->ErrorInfo;
			$return_data =  '{"code":111, "message":"Mailer Error"}';
		}
		else
		{
			//echo "Message has been sent";
			$return_data =  '{"code":200, "message": "Message has been sent"}';
		}

		return $return_data;

    }

	//======================================================================================
    function cm_get_forgot_password_mail_template(){
	
		$content = "Xin chào [%clientname%],<br/><br/>";

		$content .= "Vui lòng click vào link dưới để đổi password trên kiemtraduan.net:<br/>";
		$content .= '<a href="[%reset_password_link%]">Đổi Password</a><br/><br/>';
		
		$content .= "Nếu bạn không yêu cầu đổi password hoặc đã nhớ nó:<br/>";
		$content .= '<a href="[%clear_reset_password_link%]">Xóa yêu cầu đổi Password</a><br/><br/>';

		$content .="Nếu có thắc mắc vui lòng liên hệ với chúng tôi theo email <a href=\"mailto:support@bansac.vn\">support@bansac.vn</a> or visit <a=href=\"http://www.bansac.vn\">www.bansac.vn</a><br/>";
		$content .="Cảm ơn!<br/>";
		$content .="The Bansac Support Team<br/>";
		
		return $content;
	}

	/*
		Hi,
		Click the link below to reset you password:
		Password Reset

		If you didn't request this change or if you now remember your password:
		Cancel this Password Reset

		If you have any questions. please contact us at support@malevia.com or visit support.malevia.com
		Thanks!
		The Malevia Support Team

		Hyperlinks at 4 places:
		Password Reset
		Cancel this Password Reset
		support@malevia.com
		support.malevia.com
	*/

	//======================================================================================
    function cm_get_send_new_pass_mail_template(){
	
		$content = "Hi [%clientname%],<br/><br/>";

		$content .= "Password mới của bạn là: [%password%]<br/><br/>";


		$content .="Nếu bạn có bất kỳ câu hỏi, vui lòng liên lạc chúng tôi theo email support@bansac.vn<br/>";
		$content .="Thanks,<br/>";
		$content .="Bansac<br/>";
		
		return $content;
	}

	
//=========================================================================================================
function cm_encrypt($info) 
{
	

	if(strlen($info) < (PRE_LENGTH+POST_LENGTH))
		return "";
	
	
	$es1 = "";
	for($i=0;$i<PRE_LENGTH;$i++)
	{
		$ascii = sprintf('%03d', ord($info[$i]) + ASCII_DELTA );
		$es1 .= $ascii;
	}
	

	$es2 = "";
	for($i=strlen($info) - POST_LENGTH;$i<strlen($info);$i++)
	{
		$ascii = sprintf('%03d', ord($info[$i]) + ASCII_DELTA);
		$es2 .= $ascii;
	}

	$es3 = "";
	for($i=PRE_LENGTH;$i<(strlen($info)- POST_LENGTH);$i++)
	{
		$ascii = sprintf('%03d', ord($info[$i]) + ASCII_DELTA );
		$es3 .= $ascii;
	}

	//echo $es3.$es1.$es2;	
	return ($es3.$es1.$es2);

}

//=========================================================================================================
function cm_decrypt($code) 
{
	if(strlen($code) < 3*(PRE_LENGTH+POST_LENGTH))
		return "";
	
	$ds2 = "";
	for($i=(strlen($code)- 3*POST_LENGTH);$i<strlen($code);$i+=3)
	{
		$ascii = substr($code,$i,3); 
		$ds2 .= chr(intval($ascii) - ASCII_DELTA);
	}


	$ds1 = "";
	for($i=(strlen($code)- 3*(PRE_LENGTH + POST_LENGTH));$i<(strlen($code)- 3*POST_LENGTH);$i+=3)
	{
		$ascii = substr($code,$i,3); 
		$ds1 .= chr(intval($ascii) - ASCII_DELTA);
	}
	
	
	$ds3 = "";
	for($i=0;$i<(strlen($code)- 3*(PRE_LENGTH + POST_LENGTH));$i+=3)
	{
		$ascii = substr($code,$i,3); 
		$ds3 .= chr(intval($ascii) - ASCII_DELTA);
	}


	return ($ds1.$ds3.$ds2);
}



//==============================================================================================================
function cm_create_user_access_token( $UserID, $UserPassword)
{     
	$AccessToken =  "USR".$UserID."_".password_hash($UserPassword, PASSWORD_DEFAULT);
	return $AccessToken;
}


//==============================================================================================================
function cm_http_post($url,$params)
{
	$postData = '';
   //create name value pairs seperated by &
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   rtrim($postData, '&');
 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return $output;

}

//echo cm_encrypt('123456789');

?>