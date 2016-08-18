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
   $infoMsg = "";
  
   if (isset($_POST["submit"])) {
		$EmailID = $_POST['EmailID'];
		$Email = mysqli_real_escape_string($db,$_POST['Email']);
		$Password = cm_encrypt($_POST['Password']);
		$EmailType = $_POST['EmailType'];
		
		
		if(strlen($Email)==0){
			$infoMsg = "Email should not be empty";
		}
		else if(strlen($Password)==0){
			$infoMsg = "Password should not be empty";
		}
		else if($EmailType<=0){
			$infoMsg = "EmailType should be greater than zero";
		}
		else{
				
			$query  = " UPDATE  `Email` SET Email = '$Email',EmailType = $EmailType, Password = '$Password'
			WHERE  EmailID=$EmailID";
			$result = mysqli_query($db, $query);
				
			if($result){
				$infoMsg = "Updated successfully";
			}
			else{
				$infoMsg = "Updated fail";
			}	
		}
   }
   else if (isset($_POST["submit_delete"])) {
	   $EmailID = $_POST['EmailID'];
	   $query  = " UPDATE  `Email` SET RemovalFlag = 1
			WHERE  EmailID=$EmailID";
		
		$result = mysqli_query($db, $query);
	   
	   
   }
   else if (isset($_POST["add_new_mail"])) {
	   
	   $query  = " INSERT INTO `Email` (EmailType) VALUES(1)";
		$result = mysqli_query($db, $query);
		
		//var_dump($result);
		
			
   }
   
   
   //get client info
   $query  = "SELECT `Email`.*
			FROM `Email` 
			WHERE `Email`.`RemovalFlag` = 0
			ORDER BY EmailID";
	
	$result = mysqli_query($db, $query);
	
	$data   = array();  
	
	while ($row = mysqli_fetch_array($result)) {
		array_push($data,$row);
	}
	
	cm_close_connect($db);
?>


<script>
var top_menu = document.getElementById("a_toplink_admin_email");
top_menu.style.color = "White";

</script>
		
		<div class="row">
  			<div class="col-md-12 col-md-offset-0">
  				<h2 class="page-header text-center">Danh Sách Email</h2>
				
				<br/>
				<p><?php echo "<p class='text-success'>".$infoMsg."</p>";?></p>
				<p>Note: Type=1: email for admin. Type=2: email for sender </p>
			
				
				<table class="col-md-12 col-md-offset-0" border="1" style="width:100%;">
					<tr>
					<th>ID</th>
					<th>Email</th>
					<th >Password</th>
					<th >Type</th>
					<th >Update</th>
					</tr>
					
					<?php for($i=0;$i<sizeof($data);$i++){
						$row = $data[$i];
						$pass = cm_decrypt($row['Password']);
						
						
						echo "<form  role='form' method='post' action=". $_SERVER['PHP_SELF'].">
							<tr>
								
								<td> <input type='text' readonly name='EmailID' class='form-control' style='width:50px;' value='".$row['EmailID']."'></input></td>
								<td> <input type='text'  name='Email'  class='form-control' value='".$row['Email']."'></input></td>
								<td> <input type='text'  name='Password'  class='form-control' value='".$pass."'></input></td>
								<td> <input type='text'  name='EmailType'  class='form-control' style='width:50px;' value='".$row['EmailType']."'></input></td>
								<td> <button type='submit'  name='submit' class='btn btn-warning'>Change</button>
									<button type='submit'  name='submit_delete' class='btn btn-danger'>Delete</button>
								</td>
							</tr> 
							</form>";
						
					} ?>
					
				</table>

			</div>
			 
		</div>
		<br/>
		
		<div class="row">
			<div class="col-md-12 col-md-offset-0">
				<form class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<div class="form-group">
						<button type="submit" class = "col-sm-2 col-md-offset-3 btn btn-primary"   name = "add_new_mail">Add New Email</button>
						
						<button type="submit" class = "col-sm-2 col-md-offset-1 btn btn-primary btn-success"    name = "refresh">Refresh</button>
					</div>
					
				</form>
				
			</div>
		</div>

 



<?php
// do php stuff
include('template/admin-footer.php');
?>
