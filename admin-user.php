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
		$UserID = $_POST['UserID'];
		$UserEmail = mysqli_real_escape_string($db,$_POST['UserEmail']);
		$UserPassword = mysqli_real_escape_string($db,$_POST['UserPassword']);
		$UserRoleID = $_POST['UserRoleID'];
		
		
		if(strlen($UserEmail)==0){
			$infoMsg = "UserEmail should not be empty";
		}
		else if($UserRoleID<=0){
			$infoMsg = "UserRoleID should be greater than zero";
		}
		else{
				
			
			$query  = " UPDATE  `User` SET UserEmail = '$UserEmail',UserRoleID = $UserRoleID";
			
			if(strlen($UserPassword)>0){
				$UserPassword = cm_encrypt_password(md5($UserPassword));
				
				$query  .= ", UserPassword = '$UserPassword' ";
			}
			
			$query  .= " WHERE  UserID = $UserID";
			
			
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
	   $UserID = $_POST['UserID'];
	   $query  = " UPDATE  `User` SET RemovalFlag = 1
			WHERE  UserID=$UserID";
		
		$result = mysqli_query($db, $query);
	   
	   
   }
   else if (isset($_POST["add_new_user"])) {
	   
	   $UserPassword =  cm_generateRandomString();
	   $UserPassword = cm_encrypt_password(md5($UserPassword)); 
	   $query  = " INSERT INTO `User` (UserPassword,UserRoleID) VALUES('$UserPassword' ,3)";
		$result = mysqli_query($db, $query);
		
		//var_dump($result);
		
			
   }
   
   
   //get client info
   $query  = "SELECT `User`.*
			FROM `User` 
			WHERE `User`.`RemovalFlag` = 0
			ORDER BY UserID";
	
	$result = mysqli_query($db, $query);
	
	$data   = array();  
	
	while ($row = mysqli_fetch_array($result)) {
		array_push($data,$row);
	}
	
	cm_close_connect($db);
?>


<script>
var top_menu = document.getElementById("a_toplink_admin_user");
top_menu.style.color = "White";

</script>
		
		<div class="row">
  			<div class="col-md-12 col-md-offset-0">
  				<h2 class="page-header text-center">Danh Sách User</h2>
				
				<br/>
				<p><?php echo "<p class='text-success'>".$infoMsg."</p>";?></p>
				<p>Note: UserRoleID=1: Root || UserRoleID=2: ClientAdmin || UserRoleID=3: User </p>
			
				
				<table class="col-md-12 col-md-offset-0" border="1" style="width:100%;">
					<tr>
					<th>UserID</th>
					<th>UserEmail</th>
					<th >UserPassword</th>
					<th >UserRoleID</th>
					<th >Update</th>
					</tr>
					
					<?php for($i=0;$i<sizeof($data);$i++){
						$row = $data[$i];
						//$pass = cm_decrypt($row['Password']);
						
						
						echo "<form  role='form' method='post' action=". $_SERVER['PHP_SELF'].">
							<tr>
								
								<td> <input type='text' readonly name='UserID' class='form-control' style='width:50px;' value='".$row['UserID']."'></input></td>
								<td> <input type='text'  name='UserEmail'  class='form-control' value='".$row['UserEmail']."'></input></td>
								<td> <input type='text'  name='UserPassword'  class='form-control' value=''></input></td>
								<td> <input type='text'  name='UserRoleID'  class='form-control' style='width:50px;' value='".$row['UserRoleID']."'></input></td>
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
						<button type="submit" class = "col-sm-2 col-md-offset-3 btn btn-primary"   name = "add_new_user">Add New User</button>
						
						<button type="submit" class = "col-sm-2 col-md-offset-1 btn btn-primary btn-success"    name = "refresh">Refresh</button>
					</div>
					
				</form>
				
			</div>
		</div>

 



<?php
// do php stuff
include('template/admin-footer.php');
?>
