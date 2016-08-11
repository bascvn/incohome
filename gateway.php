<?php

// Require the initialisation file
require_once 'common.php';

if(isset($_GET['controller']))
{
	

	$url=explode('.',$_GET['controller']);
	if(count($url)<2)
		return;
	
	
	
	/*
	//passed APIs
	if( 
		(strcmp($url[0],'customer')==0 && strcmp($url[1],'check_login')==0) //customer.check_login
		||(strcmp($url[0],'customer')==0 && strcmp($url[1],'forgot_password')==0) //customer.forgot_password
	)
*/
	
	{
		//$url=explode('.',$_GET['controller']);
		require_once 'api/' . $url[0] .'.php';    
		
		
	}


/*
	//handle access token for some APIs
	if(isset($_POST['AccessToken']))
	{
	
		 
		 $AccessToken = $_POST['AccessToken'];
		 if(strlen($AccessToken)==0)
		{
			 echo '{"status":606,"message":"Invalid Access Token, please sign in again."}';
			 return;
		}

		 $pos = strpos($AccessToken, "_");
		 $infoStr = substr($AccessToken,0,$pos); //e.g CUS1_...
		 $UserType = substr($infoStr,0,3);
		 $UserID = substr($infoStr,3);
		 $DB_AccessToken = "";
		 $PostUserID = '';

		$db = cm_connect();
		if(strcmp($UserType,"CUS")==0)
		{
			$query = "SELECT *  fROM `Customer` WHERE CustomerID = '$UserID'";
			if(isset($_POST['CustomerID']))
				$PostUserID = $_POST['CustomerID'];
		}
		else if(strcmp($UserType,"USR")==0)
		{
			$query = "SELECT *  fROM `User` WHERE UserID = '$UserID'";

			//user.reset_password
			if(strcmp($url[0],'user')==0 && strcmp($url[1],'reset_password')==0) //reset password for user
			{
				if(isset($_POST['AdminID']))
					$PostUserID = $_POST['AdminID'];
			}
			else
			{
				if(isset($_POST['UserID']))
					$PostUserID = $_POST['UserID'];
			}
		}
		else
		{
			echo '{"status":606,"message":"Invalid Access Token"}';
			 return;
		}

		
		$result = mysqli_query($db, $query);
		while ($row = mysqli_fetch_array($result)) {
			$DB_AccessToken = $row['AccessToken'];
		}
		cm_close_connect($db);

		if(strcmp($PostUserID,$UserID)!=0 || strcmp($AccessToken,$DB_AccessToken)!=0 ) 
		{
			echo '{"status":606,"message":"Invalid Access Token"}';
			return;
		}
		else
		{
			//echo "OK";
			$url=explode('.',$_GET['controller']);
			require_once 'api/' . $url[0] .'.php';    
			return;
		}
		
		return;
	}
*/
	
}

?>