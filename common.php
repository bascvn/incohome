﻿<?php 

	require_once('PHPMailer/PHPMailerAutoload.php');
	
	 define("PRE_LENGTH",      2); 
	 define("POST_LENGTH",     3);
	 define("ASCII_DELTA",     9); 	
	 
	 define("SRV_PROTOCOL", "http://"); // this is used for DEV
	 
	 
	 //for DEV 
	 define("INCO_DOMAIN", "localhost"); // this is used for DEV
	 define("SUB_INCO_PATH", "inco"); // this is used for DEV
	 define("HOST_OS", "window"); // this is used for DEV
	 define("SUPPORT_EMAIL", "nvlong@gmail.com"); // this is used for DEV
	 define("FROM_EMAIL", "support@kiemtraduan.net"); // this is used for DEV
	 define("ROOT_SERVER_PATH", "C:/wamp/www/"); // this is used for DEV
	 define("INCO_UPLOAD_PATH", "/uploads/"); // this is used for DEV
	 
	 
	 
	 
	 /*
	  // for PROD
	 define("INCO_DOMAIN", "kiemtraduan.net");  
	 define("SUB_INCO_PATH", "");  
	 define("HOST_OS", "linux");  
	 define("SUPPORT_EMAIL", "support@bansac.vn"); 
	 define("FROM_EMAIL", "support@kiemtraduan.net"); 
	 define("ROOT_SERVER_PATH", "/var/www/kiemtraduan.net/public_html/"); 
	 define("INCO_UPLOAD_PATH", "/uploads/");
	 */
	 
	 
	 
	
	//======================================================================================
    function cm_get_server_uri(){
		$actual_link = SRV_PROTOCOL."$_SERVER[HTTP_HOST]/".SUB_INCO_PATH;
		return $actual_link;
	}
	
	//==============================================================================================================
	function cm_get_full_api_url($ClientCode, $target){
		
		//$_uri = SRV_PROTOCOL.$ClientCode.".".INCO_DOMAIN ."/". SUB_INCO_PATH ."/gateway.php?controller=".$target;
		
		
		//for DEV 
		//$_uri = "http://localhost/inco/gateway.php?controller=client.get_used_memory"; 
		$_uri = SRV_PROTOCOL.INCO_DOMAIN ."/". SUB_INCO_PATH ."/gateway.php?controller=".$target;
		
		return 	$_uri;	
	}

	
	//======================================================================================
    function cm_connect(){
		
		// Create connection
		 $con=mysqli_connect('127.0.0.1','root','12345','incoclient');
		// $con=mysqli_connect('127.0.0.1','incoclient','~vbfgrt45@','incoclient');
		   


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
			$return_data =  '{"status":404, "message":"Mailer Error"}';
		}
		else
		{
			//echo "Message has been sent";
			$return_data =  '{"status":200, "message": "Message has been sent"}';
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
function cm_encrypt_password($input, $rounds = 7)
{
	$salt = "";
	$salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
	for($i=0; $i < 22; $i++) {
	  $salt .= $salt_chars[array_rand($salt_chars)];
	}
	return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
}

//==============================================================================================================
function cm_verify_password($password_entered, $password_hash)
{
	return (crypt($password_entered, $password_hash) == $password_hash);
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
	curl_setopt($ch, CURLOPT_ENCODING, '');
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	 
	
	/*
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData)));
	*/
	
    $output=curl_exec($ch);
	//$output=curl_exec_utf8($ch);
 
    curl_close($ch);
    return $output;
}


//==============================================================================================================
/** The same as curl_exec except tries its best to convert the output to utf8 **/
function curl_exec_utf8($ch) {
    $data = curl_exec($ch);
    if (!is_string($data)) return $data;

    unset($charset);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    /* 1: HTTP Content-Type: header */
    preg_match( '@([\w/+]+)(;\s*charset=(\S+))?@i', $content_type, $matches );
    if ( isset( $matches[3] ) )
        $charset = $matches[3];

    /* 2: <meta> element in the page */
    if (!isset($charset)) {
        preg_match( '@<meta\s+http-equiv="Content-Type"\s+content="([\w/]+)(;\s*charset=([^\s"]+))?@i', $data, $matches );
        if ( isset( $matches[3] ) )
            $charset = $matches[3];
    }

    /* 3: <xml> element in the page */
    if (!isset($charset)) {
        preg_match( '@<\?xml.+encoding="([^\s"]+)@si', $data, $matches );
        if ( isset( $matches[1] ) )
            $charset = $matches[1];
    }

    /* 4: PHP's heuristic detection */
    if (!isset($charset)) {
        $encoding = mb_detect_encoding($data);
        if ($encoding)
            $charset = $encoding;
    }

    /* 5: Default for HTML */
    if (!isset($charset)) {
        if (strstr($content_type, "text/html") === 0)
            $charset = "ISO 8859-1";
    }

    /* Convert it if it is anything but UTF-8 */
    /* You can change "UTF-8"  to "UTF-8//IGNORE" to 
       ignore conversion errors and still output something reasonable */
    if (isset($charset) && strtoupper($charset) != "UTF-8")
        $data = iconv($charset, 'UTF-8', $data);

    return $data;
}

//==============================================================================================================
function cm_get_dir_size_readable($path)
{
	$os = HOST_OS;
	$m_size = 0;
	$Bsize = 0;
	
	
	if(strcmp($os,"window")==0){
		//$f = 'f:/www/docs';
		$obj = new COM ( 'scripting.filesystemobject' );
		if ( is_object ( $obj ) )
		{
			$ref = $obj->getfolder ( $path );
			$Bsize = $ref->size;
			$obj = null;	
		}
		
		
		cm_convert_byte_to_readable($Bsize);
	
		
	}else if(strcmp($os,"linux")==0){
		
		$io = popen ( '/usr/bin/du -sh ' . $path, 'r' );
		$size = fgets ( $io, 4096);
		$Bsize = substr ( $size, 0, strpos ( $size, "\t" ) );
		pclose ( $io );
		//echo 'Directory: ' . $f . ' => Size: ' . $size;
		return $Bsize; 
	}
	
	
			
	
	return "";
}


//==============================================================================================================
function cm_get_dir_size($path)
{
	$os = HOST_OS;
	$m_size = 0;
	$Bsize = 0;
	
	if(strcmp($os,"window")==0){
		//$f = 'f:/www/docs';
		$obj = new COM ( 'scripting.filesystemobject' );
		if ( is_object ( $obj ) )
		{
			$ref = $obj->getfolder ( $path );
			$Bsize = $ref->size;
			$obj = null;	
		}
	}else if(strcmp($os,"linux")==0){
		
		$io = popen ( '/usr/bin/du -sb ' . $path, 'r' );
		$size = fgets ( $io, 4096);

		$Bsize = substr ( $size, 0, strpos ( $size, "\t" ) );
		pclose ( $io );	

	}
				
	
	return $Bsize;
}


//==============================================================================================================
function cm_convert_byte_to_readable($Bsize){
	if($Bsize>=1024)
	{
		$Msize = $Bsize / (1024*1024);
		
		if($Msize<1){
			$Ksize =  number_format($Msize*1024, 2, '.', '');
			return $Ksize . "KB";
		}
		else if($Msize>1024){
			
			 $Gsize =  number_format($Msize/1024, 2, '.', '');
			return ($Gsize . "GB");
		}
		else{
			$Msize =  number_format($Msize, 2, '.', '');	
			return $Msize . "MB" ;
		}
	}
	
	if($Bsize>0)
		return $Bsize. " B";
	else 
		return "0B";
}



//echo cm_get_dir_size(ROOT_SERVER_PATH."incodemo" );
//echo cm_encrypt_password('1309929f39d74f243cf6c6e6d54f6bc9');
//echo cm_decrypt('060061062063058059064065066');


?>