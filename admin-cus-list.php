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
   $query  = "SELECT `Client`.*,`Package`.PackageName, `AdEmail`.Email AS AdminEmail 
			FROM `Client`,`Package`,`Email` AS `AdEmail`
			WHERE `Client`.`RemovalFlag` = 0  AND `Package`.`PackageID` =  `Client`.`PackageID`
			AND `AdEmail`.`EmailID` =  `Client`.`AdminEmailID`
			ORDER BY `Client`.ClientCode";
	
	$result = mysqli_query($db, $query);
	
	$client_data   = array();  
	
	while ($row = mysqli_fetch_array($result)) {
		array_push($client_data,$row);
	}
	
	
	//get package 
	$query  = "SELECT `Package`.*
			FROM `Package`
			WHERE   `Package`.RemovalFlag = 0";
	
	//echo $query;
	
	$result = mysqli_query($db, $query);
	$package_data   = array();        
	while ($row = mysqli_fetch_array($result)) {
		array_push($package_data,$row);
	}

	
	//get email list 
	$query  = "SELECT `Email`.*
			FROM `Email`
			WHERE   `Email`.RemovalFlag = 0";
	
	//echo $query;
	
	$result = mysqli_query($db, $query);
	$email_data   = array();        
	while ($row = mysqli_fetch_array($result)) {
		array_push($email_data,$row);
	}
	
	cm_close_connect($db);
	
?>


<script>
var top_menu = document.getElementById("a_toplink_admin_cus_list");
top_menu.style.color = "White";

</script>

		
		<div class="row">
  			<div class="col-md-12 col-md-offset-0">
  				<h2 class="page-header text-center">Danh Sách Khách Hàng</h2>
				
				<table class="col-md-12 col-md-offset-0" border="1" style="width:100%;">
					<tr>
					<th>ID</th>
					<th>Mã Công Ty</th>
					<th >Tên Công Ty</th>
					<th >Tình Trạng</th>
					<th >Contact Email</th>
					<th >Admin Email</th>
					<th >Action</th>
					
					</tr>
					
					<?php for($i=0;$i<sizeof($client_data);$i++){
						$row = $client_data[$i];
						
						
						echo "<tr><form  role='form' method='POST' action='admin-customer.php'>
				
								<td><input class='form-control' id='ClientID' name='ClientID' value='".$row['ClientID']."'   readonly='readonly' style='width:50px;' ></td>
								<td><input class='form-control' id='ClientCode' name='ClientCode' value='".$row['ClientCode']."'   readonly='readonly' ></td>
								<td>".$row['ClientName']."</td>
								<td><div class='img-circle' style='width:100%; margin:0 auto; background-color: "
								.($row['Status']==0 ?"red": (($row['Status']==1) ? "green":"yellow"))
								."; width: 20px; text-align: center;'>". $row['Status']."</div></td>
								
								<td>".$row['ContactEmail']."</td>
								<td>".$row['AdminEmail']."</td>
								
								<td> 
									<button type='submit'  name='view_client' class='btn btn-success'>View/Edit</button>
									<button type='submit'  name='delete_client' class='btn btn-danger' id='bt_delete_client' >Delete</button>
								</td>
							</form></tr>";
						
					} ?>
					
				</table>

			</div>
			 
		</div>
		<br/>
		<div class="row">
			<div class="col-md-6 col-md-offset-5">
					
					<a href="#" class="btn btn-primary" 
								data-toggle="modal" 
								data-target="#addNewClientModal"
								data-backdrop="static" data-keyboard="false">Add New Client</a>
						
			</div>
			
		</div>

		
		

<?php
// do php stuff
include('template/admin-footer.php');
?>

<?php
// do php stuff
include('template/form-admin-add-client.php');
?>

<?php
// do php stuff
include('template/footer-common.php');
?>

