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
	try{
		$ClientCode = $_POST["ClientCode"];
		$DBName = $_POST["DBName"];
		$DBUser = $_POST["DBUser"];
		$DBPass = $_POST["DBPass"];
		
		$used_memory = get_used_memory($ClientCode);
		
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
			
			
			//header('Content-Type: application/json; charset=utf-8');
			//echo json_encode('{"status":200,"message":"ok","used_memory": "'.$used_memory.'","user_count":'. $UserCount.'}');
			echo '{"status":200,"message":"ok","used_memory": "'.$used_memory.'","user_count":"'. $UserCount.'"}';
		}
	
	
	}catch(Exception $e){
		echo $e;
	}finally {
		mysqli_close($db);
	}	
}


?>