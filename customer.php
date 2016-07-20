<?php
// do php stuff
include('template/header.php');
?>

<?php

   if( !isset($_SESSION['valid'])  || !$_SESSION['valid']){
		header("Location: signin.php");
		die();
   }
   
   $ContactEmail = $_SESSION['username'];
   $ClientCode = $_SESSION['clientcode'];
   $ClientName ='';
   
   
   
   $db     = cm_connect();
   $query  = "SELECT `Client`.*
			FROM `Client`
			WHERE LOWER(`Client`.`ContactEmail`) = LOWER('$ContactEmail') AND `Client`.ClientCode = '$ClientCode'  AND `Client`.`RemovalFlag` = 0  ";
	
	$result = mysqli_query($db, $query);
	//$data   = array();        
	$found_client = false;
	while ($row = mysqli_fetch_array($result)) {
		 $ClientName = $row['ClientName'];
	}
	
	if(!$found_client){
		$msg = 'Công ty không đã hết hạn.';
	}
		
	cm_close_connect($db);
		
?>


<script>
var top_menu = document.getElementById("a_toplink_customer");
top_menu.style.color = "White";
</script>


		<h4 ><?php echo "<p>$ClientName</p>";?></h4>
		

<?php
// do php stuff
include('template/footer.php');
?>
