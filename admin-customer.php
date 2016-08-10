<?php
// do php stuff
include('template/admin-header.php');
?>

<?php

   if( !isset($_SESSION['admin-valid'])  || !$_SESSION['admin-valid']){
		header("Location: admin.php");
		die();
   }
   
   //$ContactEmail = $_SESSION['username'];
   
   $ClientCode = '';
   if(isset($_GET['clientcode'])){
		$ClientCode = $_GET['clientcode'];
	}else if(isset($_POST['ClientCode'])){
		$ClientCode = $_POST['ClientCode'];
	}
	else{
		header("Location: admin-cus-list.php");
		die();
	}

   $ClientName ='';
   $ContactPhone = '';
   $secondFromGMT = 0;
   $DateCreated = 0;
   $DateUpdated = 0;
   $DateExpired = 0;
   $MaxGB = 0;
   $MaxUser = 0;
   $PackageID = 0;
   $PackageName = '';
   $ClientID = 0;
   $DBName = '';
   $DBUser = '';
   $DBPassword = '';
   $result_msg = '';
   $tz_name = "Asia/Ho_Chi_Minh";
   $tz = new DateTimeZone($tz_name);
  
   $db     = cm_connect();
   
   
   //change values
   if (isset($_POST["submit"])) {
		
		$ClientName = $_POST['ClientName'];
		$ContactPhone = $_POST['ContactPhone'];
		$ContactEmail = $_POST['ContactEmail'];
		
		$NewPassword = $_POST['NewPassword'];
		$ReNewPassword = $_POST['ReNewPassword'];	
		
		$DateCreated = $_POST['DateCreated'];
		$DateCreatedObj = date_create_from_format("d/m/Y",$DateCreated);
		//echo  $DateCreatedObj->getTimestamp();
		
		$DateUpdated = $_POST['DateUpdated'];
		$DateUpdatedObj = date_create_from_format("d/m/Y",$DateUpdated);
		
		
		$DateExpired = $_POST['DateExpired'];
		$DateExpiredObj = date_create_from_format("d/m/Y",$DateExpired);
		
		$MaxUser =  $_POST['MaxUser'];
		$MaxGB  =  $_POST['MaxGB'];
		$ClientPackageID =  $_POST['ClientPackageID'];
		$NoreplyEmailID =  $_POST['NoreplyEmailID'];
		$AdminEmailID = $_POST['AdminEmailID'];
		
		$DBName = $_POST['DBName']; 
		$DBUser = $_POST['DBUser']; 
		$DBPassword = cm_encrypt($_POST['DBPassword']); 
		
		
		$doUpdate = true;
		$pass_hash = '';
		
		if (!filter_var($ContactEmail, FILTER_VALIDATE_EMAIL)) {
			$result_msg = '<div class="alert alert-warning">Email không hợp lệ.</div>'; 
			$doUpdate = false;
			
		}
		
		
		
		else if( strlen($NewPassword)>0 && strlen($ReNewPassword)>0 ){
			if(strlen($NewPassword)<6){
				$result_msg = '<div class="alert alert-warning">Password mới dưới 6 ký tự.</div>'; 
				$doUpdate = false;
			}
			else if(strcmp($NewPassword,$ReNewPassword)!=0){
				$result_msg = '<div class="alert alert-warning">Password mới và gõ lại không giống.</div>'; 
				$doUpdate = false;
			}
			
			//$pass_hash = password_hash($NewPassword, PASSWORD_DEFAULT);
			$pass_hash = cm_encrypt_password($NewPassword);
		}
		
		
			
		
		if($doUpdate){
				$query  = "UPDATE `Client` SET 
				 `Client`.ClientName = '$ClientName' 
				 ,`Client`.ContactEmail = '$ContactEmail' 
				 ,`Client`.ContactPhone = '$ContactPhone' 
				 ,`Client`.DateCreated = ".$DateCreatedObj->getTimestamp()
				 .",`Client`.DateUpdated = ".$DateUpdatedObj->getTimestamp()
				 .",`Client`.DateExpired = ".$DateExpiredObj->getTimestamp()
				 .",`Client`.MaxUser = '$MaxUser'" 
				 .",`Client`.MaxGB = '$MaxGB'" 
				 .",`Client`.PackageID = '$ClientPackageID'" 
				 .",`Client`.NoreplyEmailID = '$NoreplyEmailID'" 
				.",`Client`.AdminEmailID = '$AdminEmailID'" 
				.",`Client`.DBName = '$DBName'" 
				.",`Client`.DBUser = '$DBUser'" 
				.",`Client`.DBPassword = '$DBPassword'" 
			
				 .(strlen($pass_hash)>0?"  ,`Client`.ContactPassword = '$pass_hash'  ":"" )
				 ." WHERE `Client`.ClientCode = '$ClientCode'";
				 
			
				 $result = mysqli_query($db, $query);
				if($result){
					$result_msg ='<div class="alert alert-success">Đã cập nhật thành công.</div>';
				}else{
					$result_msg ='<div class="alert alert-warning">Không thể cập nhật được.</div>';
				}
		
		}
	
   }
   else    if (isset($_POST["submit_change_payment"])) {
	   $PaymentHistoryID =  $_POST['PaymentHistoryID']; 
	   $PayDescription =  $_POST['PayDescription']; 
	   
	   $PaymentDate = $_POST['PaymentDate'];
		$PaymentDateObj = date_create_from_format("d/m/Y",$PaymentDate);
		//echo  $DateCreatedObj->getTimestamp();
		
	   
	   $query  = "UPDATE `PaymentHistory` SET 
				 `PaymentHistory`.Description = '$PayDescription' "
				  .",`PaymentHistory`.DateTime = ".$PaymentDateObj->getTimestamp()
				 ." WHERE `PaymentHistory`.PaymentHistoryID = '$PaymentHistoryID'";
		 
			
		$result = mysqli_query($db, $query);
		if($result){
			$result_msg ='<div class="alert alert-success">Đã cập nhật thành công.</div>';
		}else{
			$result_msg ='<div class="alert alert-warning">Không thể cập nhật được.</div>';
		}
				
   }
   else if (isset($_POST["submit_delete_payment"])) {
	    $PaymentHistoryID =  $_POST['PaymentHistoryID']; 
		
		 $query  = "UPDATE `PaymentHistory` SET 
				 `PaymentHistory`.RemovalFlag = 1 "
				 ." WHERE `PaymentHistory`.PaymentHistoryID = '$PaymentHistoryID'";
		 
			
		$result = mysqli_query($db, $query);
		if($result){
			$result_msg ='<div class="alert alert-success">Đã cập nhật thành công.</div>';
		}else{
			$result_msg ='<div class="alert alert-warning">Không thể cập nhật được.</div>';
		}
		
   }
   
   
   //get client info
   $query  = "SELECT `Client`.*,`Package`.PackageName, `AdEmail`.Email AS `AdminEmail`, `NoRpEmail`.Email AS `NoreplyEmail` 
			FROM `Client`,`Package`,`Email` AdEmail,  `Email` NoRpEmail
			WHERE  `Client`.ClientCode = '$ClientCode'  AND `Client`.`RemovalFlag` = 0 
			AND `Package`.`PackageID` =  `Client`.`PackageID`
			AND `AdEmail`.`EmailID` =  `Client`.`AdminEmailID` 
			AND `NoRpEmail`.`EmailID` =  `Client`.`NoreplyEmailID` ";
	
	$result = mysqli_query($db, $query);
	//$data   = array();        
	$found_client = false;
	while ($row = mysqli_fetch_array($result)) {
		$ClientID =  $row['ClientID'];
		 $ClientName = $row['ClientName'];
		 $ContactEmail =  $row['ContactEmail'];
		 $ContactPhone = $row['ContactPhone'];
		 $secondFromGMT = $row['GMT']; 
		 
		$DateCreated_Col =  $row['DateCreated'];
		$dt = new DateTime("@$DateCreated_Col");
		$dt->setTimezone($tz);
		$DateCreated =  $dt->format('d/m/Y');
		
		$DateUpdated_Col =  $row['DateUpdated'];		
		$dt = new DateTime("@$DateUpdated_Col");
		$dt->setTimezone($tz);
		$DateUpdated =  $dt->format('d/m/Y');
		 
		//$DateExpired =  $row['DateExpired']; 
		$DateExpired_Col =  $row['DateExpired'];		
		$dt = new DateTime("@$DateExpired_Col");
		$dt->setTimezone($tz);
		$DateExpired =  $dt->format('d/m/Y');
		
		 
		 $MaxGB = $row['MaxGB']; 
		 $MaxUser = $row['MaxUser']; 
		 $PackageName = $row['PackageName']; 
		 $PackageID = $row['PackageID']; 
		 
		$DBName = $row['DBName']; 
		$DBUser = $row['DBUser']; 
		$DBPassword = cm_decrypt($row['DBPassword']); 
		
		$AdminEmailID = $row['AdminEmailID']; 
		$AdminEmail= $row['AdminEmail']; 
		
		$NoreplyEmailID = $row['NoreplyEmailID']; 
		$NoreplyEmail = $row['NoreplyEmail']; 
		
	}
	
	if(!$found_client){
		$msg = 'Công ty đã hết hạn.';
	}else{
		
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
	}
	
	
	$DateCurrent=time();
	$DateUsed =   floor(($DateCurrent - $DateUpdated_Col) / (24*60*60));
	$DateTotal = ceil(($DateExpired_Col - $DateUpdated_Col) / (24*60*60));
	
	
	//get used memory
	$params_arr = array(
			'ClientCode' => $ClientCode
			,'DBName' => $DBName
			,'DBUser' => $DBUser
			,'DBPass' => $DBPassword
			
	);

	
	$query  = "SELECT `PaymentHistory`.*
			FROM `PaymentHistory`
			WHERE `PaymentHistory`.ClientID = $ClientID AND `PaymentHistory`.RemovalFlag = 0
			ORDER BY `PaymentHistory`.`DateTime` DESC";
	
	//echo $query;
	
	$result = mysqli_query($db, $query);
	$payment_data   = array();        
	while ($row = mysqli_fetch_array($result)) {
		array_push($payment_data,$row);
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
	
	//var_dump($params_arr);
	
	$uri_get_user_info = cm_get_full_api_url($ClientCode, "client.get_client_info"); 
	//$uri_get_user_info =  "http://incodemo.kiemtraduan.net/gateway.php?controller=client.get_client_info";
	$UserInfo = cm_http_post($uri_get_user_info,$params_arr);	
	 
	$UserInfo = substr($UserInfo, strpos($UserInfo,"{"));
	$UserInfo_Json = json_decode($UserInfo,true);
	//var_dump($UserInfo_Json);
	
	$used_memory = 0;
	$user_count = 0;
	$upload_size = 0;
	$db_size = 0;
	
	if ($UserInfo_Json['status'] == 200){
		$upload_size = $UserInfo_Json['upload_size'];
		$db_size = $UserInfo_Json['db_size'];
		$used_memory = $upload_size + $db_size;
		
		
		$user_count = $UserInfo_Json['user_count'];
		
	}
	
?>


<script>
//var top_menu = document.getElementById("a_toplink_customer");
//top_menu.style.color = "White";
</script>

		<h1><a href="admin-cus-list.php" class="glyphicon glyphicon-backward"></a></h1>
		
		<h1 class="page-header text-center"><?php echo "<p>$ClientName</p>";?>
		</h1>	

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<?php echo $result_msg; ?>	
			</div>
		</div>	
				
		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h2 class="page-header text-center">Thống Kê Sử Dựng</h2>
				
				<form class="form-horizontal" role="form" method="post" >
					
					<div class="form-group">
						<label for="createdate" class="col-sm-4 control-label">Số User Đã Tạo:</label>
						<div class="col-sm-8">
							<label name="createdate" class="control-label"><?php echo $user_count ." / ".$MaxUser ; ?></label>
							
							
							<div class="progress">
							  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php   echo $user_count; ?>"
							  aria-valuemin="0" aria-valuemax="<?php echo $MaxUser ; ?>" style="width:<?php echo (($user_count*100)/$MaxUser);  ?>%">
								
							  </div>
							</div>

						</div>
					</div>
					
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Dung Lượng Đã Dùng:</label>
						<div class="col-sm-8">
							<label name="" class="control-label"><?php echo cm_convert_byte_to_readable($used_memory)." / ".$MaxGB." GB" ; ?> </label>
							<p>
								<label name="" class="control-label">Upload File: <?php echo cm_convert_byte_to_readable($upload_size); ?> </label>
							</p>
							
							<p>
								<label name="" class="control-label">Database: <?php echo cm_convert_byte_to_readable($db_size); ?> </label>
							</p>
							
							<div class="progress">
							  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php   echo $used_memory; ?>"
							  aria-valuemin="0" aria-valuemax="<?php echo ($MaxGB*1024*1024*1024) ; ?>" style="width:<?php echo (($used_memory*100)/($MaxGB*1024*1024*1024));  ?>%">
								<!--
								<?php echo $user_count ." / ".$MaxUser ; ?>
								-->
								
							  </div>
							</div>
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Số Ngày Sử Dụng:</label>
						<div class="col-sm-8">
							
							<label name="" class="control-label"><?php echo $DateUsed." / ".$DateTotal ; ?> </label>
							
							<div class="progress">
							  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php   echo $DateUsed; ?>"
							  aria-valuemin="0" aria-valuemax="<?php echo $DateTotal ; ?>" style="width:<?php echo (($DateUsed*100)/$DateTotal);  ?>%">
								<!--
								<?php echo $DateUsed." / ".$DateTotal ; ?>
								-->
							  </div>
							</div>
							
							
						</div>
					</div>
					
					
					
					<div class="form-group">
						<div class="col-sm-12 col-sm-offset-5">
							<a href="#" class="btn btn-primary" 
								data-toggle="modal" 
								data-target="#basicModal"
								data-backdrop="static" data-keyboard="false">Nâng Cấp</a>
						</div>
					</div>
					
				</form> 
			</div>
		</div>
		
		<!-- editable -->
		
		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h2 class="page-header text-center">Thông Tin Tài Khoản</h2>
				
				<form class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					
					<input type="hidden" name="ClientID" value="<?php echo $ClientID; ?>"/>
					  
					<div class="form-group">
						<label for="createdate" class="col-sm-4 control-label">Mã Công Ty:</label>
						<div class="col-sm-8">
							<input class="form-control" id="ClientCode" name="ClientCode" value="<?php echo $ClientCode; ?>"  readonly="readonly" >
						</div>
					</div>
					
					<div class="form-group">
						<label for="createdate" class="col-sm-4 control-label">Ngày Tạo:</label>
						<div class="col-sm-8">
							<input  class="form-control" type="text" data-role="date" id="DateCreated" name="DateCreated" 
							readonly="readonly" data-inline="true" style="background-color : #ffffff;"
								value="<?php echo $DateCreated; ?>">
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Ngày Cập Nhật:</label>
						<div class="col-sm-8">
							<input  class="form-control" type="text" data-role="date" id="DateUpdated" name="DateUpdated" 
							readonly="readonly" data-inline="true" style="background-color : #ffffff;"
								value="<?php echo $DateUpdated; ?>">
								
						</div>
					</div>
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Ngày Hết Hạn:</label>
						<div class="col-sm-8">
							
							
							<input  class="form-control" type="text" data-role="date" id="DateExpired" name="DateExpired" 
							readonly="readonly" data-inline="true" style="background-color : #ffffff;"
								value="<?php echo $DateExpired; ?>">
								
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="ClientPackageID" class="col-sm-4 control-label">Gói Hiện Tại:</label>
						<div class="col-sm-8">
							
							<select class="form-control" name="ClientPackageID" id="ClientPackageID">
							<option value="<?php echo $PackageID; ?>"> <?php echo $PackageName; ?> </option>

<?php
							for($i=0;$i<sizeof($package_data);$i++){
								$package = $package_data[$i];
								if($package['AdditionalType'] == 0){
									echo "<option value='". $package['PackageID']  ."'>". $package['PackageName']."</option>";	
								}
							}				
?>
							</select>
							
							
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Lưu Trữ Tối Đa (GB):</label>
						<div class="col-sm-8">
							<input type="number" min="0" class="form-control" id="MaxGB" name="MaxGB" placeholder="0" value="<?php echo $MaxGB; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Số User Tối Đa:</label>
						<div class="col-sm-8">
							
							<input type="number" min="0" class="form-control" id="MaxUser" name="MaxUser" placeholder="0" value="<?php echo $MaxUser; ?>">
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="name" class="col-sm-4 control-label">Tên Công Ty</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="ClientName" name="ClientName" placeholder="Tên Công Ty" value="<?php echo htmlspecialchars($ClientName); ?>">
							
							
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-4 control-label">Email Liên Hệ:</label>
						<div class="col-sm-8">
							<input type="email" class="form-control" id="ContactEmail" name="ContactEmail" placeholder="example@domain.com" value="<?php echo htmlspecialchars($ContactEmail); ?>">
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="phone" class="col-sm-4 control-label">Phone</label>
						<div class="col-sm-8">
							<input type="phone" class="form-control" id="ContactPhone" name="ContactPhone" placeholder="" value="<?php echo htmlspecialchars($ContactPhone); ?>">
							
							
						</div>
					</div>
					
					
					
					<div class="form-group">
						<label for="phone" class="col-sm-4 control-label">Password Mới</label>
						<div class="col-sm-8">
							<input  type = "password"  class="form-control" id="NewPassword" name="NewPassword" placeholder="tối thiểu 6 ký tự" >
							
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="phone" class="col-sm-4 control-label">Gõ Lại Password Mới</label>
						<div class="col-sm-8">
							<input  type = "password"  class="form-control" id="ReNewPassword" name="ReNewPassword" placeholder="tối thiểu 6 ký tự" >
							
							
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="ClientPackageID" class="col-sm-4 control-label">Root Email:</label>
						<div class="col-sm-8">
							
							<select class="form-control" name="AdminEmailID" id="AdminEmailID">
							<option value="<?php echo $AdminEmailID; ?>"> <?php echo $AdminEmail; ?> </option>

<?php
							for($i=0;$i<sizeof($email_data);$i++){
								$email_item = $email_data[$i];
								if($email_item['EmailType'] == 1){
									echo "<option value='". $email_item['EmailID']  ."'>". $email_item['Email']."</option>";	
								}
							}				
?>
							</select>
							
							
						</div>
					</div>
					

					<div class="form-group">
						<label for="ClientPackageID" class="col-sm-4 control-label">Sender Email:</label>
						<div class="col-sm-8">
							
							<select class="form-control" name="NoreplyEmailID" id="NoreplyEmailID">
							<option value="<?php echo $NoreplyEmailID; ?>"> <?php echo $NoreplyEmail; ?> </option>

<?php
							for($i=0;$i<sizeof($email_data);$i++){
								$email_item = $email_data[$i];
								if($email_item['EmailType'] == 2){
									echo "<option value='". $email_item['EmailID']  ."'>". $email_item['Email']."</option>";	
								}
							}				
?>
							</select>
							
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="DBName" class="col-sm-4 control-label">DB Name:</label>
						<div class="col-sm-8">
							<input  class="form-control" id="DBName" name="DBName" placeholder="" value="<?php echo htmlspecialchars($DBName); ?>">
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="DBUser" class="col-sm-4 control-label">DB User:</label>
						<div class="col-sm-8">
							<input  class="form-control" id="DBUser" name="DBUser" placeholder="" value="<?php echo $DBUser; ?>">
							
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="DBPassword" class="col-sm-4 control-label">DB Password:</label>
						<div class="col-sm-8">
							<input  class="form-control" id="DBPassword" name="DBPassword" placeholder="" value="<?php echo $DBPassword; ?>">
							
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-12 col-sm-offset-5">
							<input id="submit" name="submit" type="submit" value="Thay Đổi" class="btn btn-primary">
						</div>
					</div>
					
				</form> 
			</div>
		</div>
	

	
		
		<div class="row">
  			<div class="col-md-12 col-md-offset-0">
  				<h2 class="page-header text-center">Lịch Sử Giao Dịch</h2>
				
				<table class="col-md-10 col-md-offset-0" border="1" style="width:100%;">
					<tr>
					<th>ID</th>
					<th>Ngày</th>
					<th>Nội Dung Giao Dịch</th>
					<th  style="text-align:right">Số Tiền (VND)</th>
					<th >Update</th>
					</tr>
					
					<?php for($i=0;$i<sizeof($payment_data);$i++){
						$row = $payment_data[$i];
						$PaymentDate = 	$row['DateTime'];
						$PayDescription = $row['Description'];
						
						
						$Subtotal =  $row['Subtotal'];
						$Discount =  $row['Discount'];
						$Tax =  $row['Tax'];
						$DiscountTax =  $row['DiscountTax'];
						
						$TotalPay = $Subtotal + $Tax  - $Discount - $DiscountTax;
						
						
						echo "
							<tr><form  role='form' method='post' action=". $_SERVER['PHP_SELF'].">
								<input type='hidden' name='ClientCode' value='".$ClientCode."' >
								<input type='hidden' name='ClientID' value='".$ClientID."'/>
							
								<td> <input type='text' readonly name='PaymentHistoryID' class='form-control' style='width:50px;' value='".$row['PaymentHistoryID']."'></input></td>
								
								<td >
									<input   style='width:100px;background-color : #ffffff;' class='PayDate form-control' type='text' data-role='date' name='PaymentDate' 
										readonly='readonly' data-inline='true' 
										value='". date('d/m/Y',  $PaymentDate ) ."'>
								</td>
								
								
								<td> <input type='text'  name='PayDescription'  class='form-control' value='".$PayDescription."'></input></td>
								
								<td style='text-align:right'>".number_format($TotalPay,0,',','.')."</td>
								
								
								<td> <button type='submit'  name='submit_change_payment' class='btn btn-warning'>Change</button>
									<button type='submit'  name='submit_delete_payment' class='btn btn-danger'>Delete</button>
								</td>
							</form></tr>";
						
					} ?>
					
				</table>
			</div>
		</div>
		
		<br/>
		<div class="form-group">
			<div class="col-sm-12 col-sm-offset-5">
				<input id="submit" name="submit"  value="Thêm Giao Dịch" class="btn btn-primary">
			</div>
		</div>

   
<!--  package upgrade dialog -->
<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog my-modal">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Nâng Cấp Sử Dụng</h4>
            </div>
            <div class="modal-body my-modal-body">
                
				<form class="form-horizontal" role="form" method="post">	
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Gói hiện tại</label>
						<div class="col-sm-7">
							<label for="name" class="col-sm-5 control-label"><?php echo $PackageName; ?></label>
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Chọn gói cao hơn</label>
						<div class="col-sm-7">
							
							<select class="form-control" name="sel_up_package" id="sel_up_package">
							<option value="0"></option>

<?php
							for($i=0;$i<sizeof($package_data);$i++){
								$package = $package_data[$i];
								
								if($package['PackageID'] >$PackageID && $package['AdditionalType'] == 0){
									echo "<option value='". $package['PackageID']  ."'>". $package['PackageName']. " - Giá: ". number_format($package['PackagePrice'],0,',','.')  ."(VND) / Tháng</option>";	
								}
							}				
?>
							</select>
	  
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Mua thêm bộ nhớ (GB)</label>
						<div class="col-sm-7">
							<input type="number" min="0" class="form-control" id="txt_add_gb" name="txt_add_gb" placeholder="1" value="0">
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Muốn đặt trên server riêng</label>
						
						<div class="col-sm-1" style="border: 0; box-shadow: none;">
						  <input type="checkbox"  class="form-control" style="border: 0; box-shadow: none;" value="" id="cb_host_own_server" name="cb_host_own_server">
						</div>
					</div>
					
					
					

					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Yêu cầu khác</label>
						<div class="col-sm-7">
								<textarea class="form-control" rows="4" name="txt_request_other" id="txt_request_other"></textarea>
						</div>
					</div>
				</form>		
					
				
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" id="bt_send_up_request"  class="btn btn-primary">Gởi chúng tôi</button>
        </div>
    </div>
  </div>
</div>



<?php
// do php stuff
include('template/admin-footer.php');
?>

<!--  package upgrade dialog -->
<Script>
	
	$("#cb_host_own_server").change(function() {
		
		if(this.checked) {
			//Do stuff
			$("#txt_add_gb").attr("disabled", "disabled"); 
			$("#sel_up_package").attr("disabled", "disabled"); 
			
			$("#txt_add_gb").val("0");
			$('#sel_up_package').val('0'); // selects "Two"
			
		}else{
			$("#txt_add_gb").removeAttr("disabled"); 
			$("#sel_up_package").removeAttr("disabled"); 
		};
	});
	
	$("#bt_send_up_request").click(function() {
		
		
		var up_package = $('#sel_up_package').val();
		var add_gb =  $("#txt_add_gb").val();
		var txt_request_other = $('#txt_request_other').val();
		var cb_host_own_server = $("#cb_host_own_server").prop('checked');
		
		if(up_package==0 && add_gb==0 && txt_request_other.length ==0 && !cb_host_own_server){
			 	
			$("#md_warning_body_text").text("Vui lòng mô tả yêu cầu nâng cấp.");
			$("#md_warning_body_text").removeClass("alert-success");
			$("#md_warning_body_text").addClass("alert-warning");
			
			$("#md_warning").modal({backdrop: 'static', keyboard: false});
			
			return;
		}
		
		var ClientCode = '<?php echo $ClientCode;?>';
		var send_content = "ClientCode "+ClientCode +" yêu cầu: \n";
		if(up_package>0){
			send_content += "Nâng cấp gói: "+ $('#sel_up_package option:selected').text() + "\n";
		}
		
		if(add_gb>0){
			send_content += "Mua thêm GB: " + add_gb +"\n";
		}
		
		if(cb_host_own_server){
			send_content += "đặt trên server riêng \n";
		}
		
		if(txt_request_other.length >0){
			send_content += "Yêu cầu khác: " + txt_request_other +"\n";
		}
		
		
		//var post_uri  = "http://localhost/inco/gateway.php?controller=client.send_upgrade_request"; 
		var post_uri  = "<?php echo cm_get_full_api_url("www", "client.send_upgrade_request");?>"; 
		$("#md_waiting").modal({backdrop: 'static', keyboard: false});
		$.post(post_uri,
			{
				ClientCode: ClientCode,
				RequestContent: send_content
			},
			
			function(data, status){
				
				$("#md_waiting").modal("toggle");
				
				//alert("Data: " + data + "\nStatus: " + status);
				$("#md_warning_body_text").text(data);
				$("#md_warning_body_text").removeClass("alert-warning");
				$("#md_warning_body_text").addClass("alert-success");
				$("#md_warning").modal({backdrop: 'static', keyboard: false});
				
			});
			
			$("#basicModal").modal("toggle");
			
	});
	
	
	$('#basicModal').on('hidden', function () {
		// do something…
		$("#cb_host_own_server").prop('checked', false);
		$("#txt_add_gb").removeAttr("disabled"); 
		$("#sel_up_package").removeAttr("disabled"); 
		$("#txt_add_gb").val("0");
		$('#sel_up_package').val('0'); 
		$('#txt_request_other').val(''); 
	});


	$('#basicModal').on('hidden.bs.modal', function () {
	  // do something…
	  	$("#cb_host_own_server").prop('checked', false);
		$("#txt_add_gb").removeAttr("disabled"); 
		$("#sel_up_package").removeAttr("disabled"); 
		$("#txt_add_gb").val("0");
		$('#sel_up_package').val('0'); 
		$('#txt_request_other').val(''); 
	})
	
	
	$( function() {
		$( "#DateCreated,#DateExpired,#DateUpdated,#PaymentDate, .PaymentDate" ).datepicker({
			dateFormat: 'dd/mm/yy'
		});
	  } );
	
	$( function() {
		$( ".PayDate" ).datepicker({
			dateFormat: 'dd/mm/yy'
		});
	  } );

</Script>
