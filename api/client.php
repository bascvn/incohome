<?php
//==============================================================================================================
// this header's code must be embeded in every AJAX controler file
//==============================================================================================================

require_once 'common.php';
require_once('PHPMailer/PHPMailerAutoload.php');
$url         = explode('.', $_GET['controller']);
$task        = $url[1];
$varFunction = "$task";
$varFunction();
//==============================================================================================================
?>


<?php
//======================================================================================
function get_used_memory($ClientCode)
{	
	return cm_get_dir_size(ROOT_SERVER_PATH.$ClientCode);
}

//======================================================================================
function get_client_info(){

	
	//update
	//try{
		$ClientCode = $_POST["ClientCode"];
		$DBName = $_POST["DBName"];
		$DBUser = $_POST["DBUser"];
		$DBPass = $_POST["DBPass"];
		$upload_size = get_used_memory($ClientCode);
		$db_size = 0;
		
		$db = mysqli_connect('127.0.0.1',$DBUser,$DBPass,$DBName);
		// Check connection
		if (mysqli_connect_errno()) {
			echo '{"status":404,"message":"could not connect database.","used_memory": "$used_memory"}';
			exit(0);
		}
		
		// Change character set to utf8
		mysqli_set_charset($db,"utf8");
		
		$query  = "SELECT COUNT(*) As UserCount FROM `users` WHERE  `users`.active = 1";
		$result = mysqli_query($db, $query);

		if($result){
			while ($row = mysqli_fetch_array($result)) {
				$UserCount = $row['UserCount']; 
				break;
			}
			
			
			$query  = "SELECT table_schema AS DBName, SUM( data_length + index_length) AS DBSizeInByte
							FROM information_schema.TABLES 
							WHERE table_schema = '$ClientCode'
							GROUP BY table_schema";

			$result = mysqli_query($db, $query);
			
			
			while ($row = mysqli_fetch_array($result)) {
				$db_size = $row['DBSizeInByte']; 
				break;
			}
			
			
			
		
			//echo json_encode('{"status":200,"message":"ok","used_memory": "'.$used_memory.'","user_count":'. $UserCount.'}');
			echo '{"status":200,"message":"ok","upload_size": "'.$upload_size.'","user_count":'. $UserCount.',"db_size":"'. $db_size . '" }';
			
		}
		mysqli_close($db);
		exit(0);
		
	
	/*
	}catch(Exception $e){
		echo $e;
	}
	finally {
		mysqli_close($db);
		//exit(0);
	}
	*/

}

//======================================================================================
function get_clients(){
	
	try{
		
	
		if(isset($_POST["SearchWord"])){	
			$SearchWord = $_POST["SearchWord"];
			if(strlen($SearchWord)<3){
				echo '{"status":500,"message":"search words should be greater than 2."}';
				return;
			}
		}
		else 
		{
			echo '{"status":550,"message":"no search word"}';
			return;
		}
		
		$db     = cm_connect();
		$query  = "SELECT  ClientName, ClientCode,Logo,Status,BuildNumber FROM Client WHERE ClientCode LIKE '%".$SearchWord."%'";
		$result = mysqli_query($db, $query);
		$data = array();
		while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
				array_push($data,$row);
		}
		
		mysqli_close($db);
		echo '{"status":200,"message":"ok","data":'.json_encode($data).'}';
	
	}catch(Exception $e){
		//echo $e;
		echo '{"status":404,"message":"fail"}';
	}
	
}

//======================================================================================
function get_client_staus(){
	
	try{
		
	
		if(isset($_POST["ClientCode"])){	
			$ClientCode = $_POST["ClientCode"];
			if(strlen($ClientCode)<3){
				echo '{"status":500,"message":"should send client code"}';
				return;
			}
		}
		else 
		{
			echo '{"status":550,"message":"no client code"}';
			return;
		}
		
		$db     = cm_connect();
		$query  = "SELECT ClientName, ClientCode,Logo,Status,BuildNumber FROM Client WHERE ClientCode = '$ClientCode'";
		$result = mysqli_query($db, $query);
		//$data = array();
		while ($row = mysqli_fetch_array($result,MYSQL_ASSOC)) {
				
				$row['msg'] ='';
				$row['url_ios'] ='';
				$row['url_android'] ='https://play.google.com/store/apps/details?id=vn.bansac.inco&hl=en';
				$row['android_ver'] ='1.1';
				$row['ios_ver'] ='';
		
				echo '{"status":200,"message":"ok","data":'.json_encode($row).'}';
				//array_push($data,$row);
				break;
		}
		
		mysqli_close($db);
		//echo '{"status":200,"message":"ok","data":'.json_encode($data).'}';
	
	}catch(Exception $e){
		//echo $e;
		echo '{"status":404,"message":"fail"}';
	}
	
}

//======================================================================================
function add_transaction(){
	
	try{
		
		$ClientID = $_POST['ClientID'];
		$TrantractionDate = $_POST['TrantractionDate'];
		$TrantractionSubtotal = $_POST['TrantractionSubtotal'];
		$TrantractionDescription = $_POST['TrantractionDescription'];
		$ClientPackageID = $_POST['ClientPackageID'];
		
		$db     = cm_connect();
		$query  = "INSERT INTO PaymentHistory(ClientID,DateTime,Subtotal,Description,PackageID) 
		VALUES($ClientID,$TrantractionDate,$TrantractionSubtotal,'$TrantractionDescription',$ClientPackageID)";
		$result = mysqli_query($db, $query);
		
		
		if($result){
			echo '{"status":200,"message":"ok"}';
		}
		else{
			echo '{"status":404,"message":"Could not insert data"}';
		}
		
		
		
		
		mysqli_close($db);
	}
	catch(Exception $e){
		//echo $e;
		echo '{"status":500,"message":"System Error"}';
	}

	
	
		
}

//======================================================================================
function send_upgrade_request(){
	
	
	$ClientCode = $_POST['ClientCode'];
	$RequestContent = $_POST['RequestContent'];
	
	$from = FROM_EMAIL; 
	$to = SUPPORT_EMAIL; 
	$subject = $ClientCode . ' request - from INCO';
	$body =$RequestContent;
	
	$toArr = array($to);
	$return_data = cm_send_mail($toArr,$subject,$body);
	echo $return_data;
	
}

//======================================================================================
function upgrade(){
	
	try{
		
		$ClientID = $_POST['ClientID'];
		$up_package = $_POST['up_package'];
		$add_gb = $_POST['add_gb'];
		$host_own_server = strcmp($_POST['host_own_server'],'true')==0;
		$upgradeFromDate = $_POST['upgradeFromDate'];
		$upgradeToDate = $_POST['upgradeToDate'];
		$add_transaction = strcmp($_POST['add_transaction'],'true')==0;
					
		
		if($host_own_server){
			$up_package = 6;
		}
	
		$db     = cm_connect();
		$query  = "UPDATE `Client`  SET MaxGB = MaxGB + $add_gb,
			DateUpdated = $upgradeFromDate,
			DateExpired = $upgradeToDate ";
			
		if($up_package>0){
			$query  .= ", PackageID = $up_package ";
		}	
		
		$query  .= " WHERE ClientID = $ClientID ";
		
		$result = mysqli_query($db, $query);
		
		
		
			
		if($result){
			echo '{"status":200,"message":"ok"}';
		}
		else{
			echo '{"status":404,"message":"Could not insert data"}';
		}
		
		if($add_transaction)
		{
			
			
			if($up_package>0){
				$ClientPackageID = $up_package;
				$TrantractionDescription = "Buy ".$ClientPackageID;
				
			}else if($add_gb >0){
				$ClientPackageID = 5; 
				//$TrantractionDescription .= ":  ".$add_gb;
				$TrantractionDescription = "Buy ".$ClientPackageID . ":  ".$add_gb;
			}
			
			$TrantractionDate =  time();
			$TrantractionSubtotal = 1000;
			
			
			
			$db     = cm_connect();
			$query  = "INSERT INTO PaymentHistory(ClientID,DateTime,Subtotal,Description,PackageID) 
			VALUES($ClientID,$TrantractionDate,$TrantractionSubtotal,'$TrantractionDescription',$ClientPackageID)";
			$result = mysqli_query($db, $query);
			
		}
		
		
		mysqli_close($db);
	}
	catch(Exception $e){
		//echo $e;
		echo '{"status":500,"message":"System Error"}';
	}
	
}


?>