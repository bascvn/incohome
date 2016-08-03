<?php
// do php stuff
include('template/admin-header.php');
?>

<?php

   if( !isset($_SESSION['admin-valid'])  || !$_SESSION['admin-valid']){
		header("Location: admin.php");
		die();
   }
   
   $db     = cm_connect();
   
   //get client info
   $query  = "SELECT `Client`.*,`Package`.PackageName
			FROM `Client`,`Package`
			WHERE `Client`.`RemovalFlag` = 0  AND `Package`.`PackageID` =  `Client`.`PackageID`";
	
	$result = mysqli_query($db, $query);
	
	$client_data   = array();  
	
	while ($row = mysqli_fetch_array($result)) {
		array_push($client_data,$row);
	}
	
	
	/*
	if($secondFromGMT!=0){
		$tz = timezone_name_from_abbr('', $secondFromGMT, 1);
		// Workaround for bug #44780
		if($tz === false) $tz = timezone_name_from_abbr('', $secondFromGMT, 0);
		// Set timezone
		date_default_timezone_set($tz);
	}
	else{
		date_default_timezone_set("UTC");
	}
		
	$DateCurrent=time();
	$DateUsed =   floor(($DateCurrent - $DateUpdated) / (24*60*60));
	$DateTotal = ceil(($DateExpired - $DateUpdated) / (24*60*60));
	*/

	cm_close_connect($db);
	
?>


<script>
var top_menu = document.getElementById("a_toplink_admin_email");
top_menu.style.color = "White";

</script>

		
		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h2 class="page-header text-center">Danh Sách Khách Hàng</h2>
				
				<table class="col-md-6 col-md-offset-0" border="1" style="width:100%;">
					<tr>
					<th>STT</th>
					<th>Mã Công Ty</th>
					<th >Tên Công Ty</th>
					<th >Tình Trạng</th>
					<th >Detail</th>
					</tr>
					
					<?php for($i=0;$i<sizeof($client_data);$i++){
						$row = $client_data[$i];
						
						
						echo "<tr>
								<td>".($i+1)."</td>
								<td>".$row['ClientCode']."</td>
								<td>".$row['ClientName']."</td>
								
								<td><div class='img-circle' style='width:100%; margin:0 auto; background-color: "
								.($row['Status']==0 ?"red": (($row['Status']==1) ? "green":"yellow"))
								."; width: 20px; text-align: center;'>". $row['Status']."</div></td>
								
								<td><a href='admin-customer.php?clientcode=". $row['ClientCode'] ."'>View / Edit<a></td>
							</tr>";
						
					} ?>
					
				</table>

			</div>
			 
		</div>
		<br/>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
					<p><button class = "btn btn-lg btn-primary btn-block"   name = "add_new-client">Add New Client</button></p>
			</div>
		</div>

 



<?php
// do php stuff
include('template/admin-footer.php');
?>
