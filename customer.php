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
  
   $db     = cm_connect();
   
   
   //change values
   if (isset($_POST["submit"])) {
		
		$client_name = $_POST['client_name'];
		$client_phone = $_POST['client_phone'];
		$client_email = $_POST['client_email'];
		$OldPassword = $_POST['OldPassword'];
		$NewPassword = $_POST['NewPassword'];
		$ReNewPassword = $_POST['ReNewPassword'];	
		
		$doUpdate = true;
		$pass_hash = '';
		
		if (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
			$result_msg = '<div class="alert alert-warning">Email không hợp lệ.</div>'; 
			$doUpdate = false;
			
		}
		
		
		
		else if(strlen($OldPassword)>0 && strlen($NewPassword)>0 && strlen($ReNewPassword)>0 ){
			if(strcmp($_SESSION['password'],$OldPassword)!=0){
				$result_msg = '<div class="alert alert-warning">Password cũ không đúng.</div>'; 
				$doUpdate = false;
			}
			else if(strlen($NewPassword)<6){
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
				 `Client`.ClientName = '$client_name' 
				 ,`Client`.ContactEmail = '$client_email' 
				 ,`Client`.ContactPhone = '$client_phone' "
				 .(strlen($pass_hash)>0?"  ,`Client`.ContactPassword = '$pass_hash'  ":"" )
				 ." WHERE `Client`.ClientCode = '$ClientCode'";
				 
			
				 $result = mysqli_query($db, $query);
				if($result){
					$ContactEmail = $_SESSION['username'] =  $client_email;
					if(strlen($pass_hash)>0){
						$_SESSION['password'] = $NewPassword; 
					}
					
					$result_msg ='<div class="alert alert-success">Đã cập nhật thành công.</div>';
				}else{
					$result_msg ='<div class="alert alert-warning">Không thể cập nhật được.</div>';
				}
		
		}
		 
		 
		 
   }
   
   
   //get client info
   $query  = "SELECT `Client`.*,`Package`.PackageName
			FROM `Client`,`Package`
			WHERE LOWER(`Client`.`ContactEmail`) = LOWER('$ContactEmail') AND `Client`.ClientCode = '$ClientCode'  AND `Client`.`RemovalFlag` = 0 
			AND `Package`.`PackageID` =  `Client`.`PackageID`";
	
	$result = mysqli_query($db, $query);
	//$data   = array();        
	$found_client = false;
	while ($row = mysqli_fetch_array($result)) {
		$ClientID =  $row['ClientID'];
		 $ClientName = $row['ClientName'];
		 $ContactPhone = $row['ContactPhone'];
		 $secondFromGMT = $row['GMT']; 
		 $DateCreated =  $row['DateCreated']; 
		 $DateUpdated =  $row['DateUpdated']; 
		 $DateExpired =  $row['DateExpired']; 
		 $MaxGB = $row['MaxGB']; 
		 $MaxUser = $row['MaxUser']; 
		 $PackageName = $row['PackageName']; 
		 $PackageID = $row['PackageID']; 
		 
		$DBName = $row['DBName']; 
		$DBUser = $row['DBUser']; 
		$DBPassword = cm_decrypt($row['DBPassword']); 
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
	$DateUsed =   floor(($DateCurrent - $DateUpdated) / (24*60*60));
	$DateTotal = ceil(($DateExpired - $DateUpdated) / (24*60*60));
	
	
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
var top_menu = document.getElementById("a_toplink_customer");
top_menu.style.color = "White";
</script>

		
		
		
				
		<h1 class="page-header text-center"><?php echo "<p>$ClientName</p>";?></h1>	

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
								<!--
								<?php echo $user_count ." / ".$MaxUser ; ?>
								-->
								
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
		
		
		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h2 class="page-header text-center">Thông Tin Tài Khoản</h2>
				
				<form class="form-horizontal" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					
					<div class="form-group">
						<label for="createdate" class="col-sm-4 control-label">Mã Công Ty:</label>
						<div class="col-sm-8">
							<label name="createdate" class="control-label"><?php echo  $ClientCode; ?></label>
						</div>
					</div>
					
					<div class="form-group">
						<label for="createdate" class="col-sm-4 control-label">Ngày Tạo:</label>
						<div class="col-sm-8">
							<label name="createdate" class="control-label"><?php echo date('d/m/Y', $DateCreated ); ?></label>
						</div>
					</div>
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Ngày Cập Nhật:</label>
						<div class="col-sm-8">
							<label name="updatedate" class="control-label"><?php echo date('d/m/Y',  $DateUpdated ); ?></label>
						</div>
					</div>
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Ngày Hết Hạn:</label>
						<div class="col-sm-8">
							<label name="updatedate" class="control-label"><?php echo date('d/m/Y',  $DateExpired ); ?></label>
						</div>
					</div>
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Gói Hiện Tại:</label>
						<div class="col-sm-8">
							<label name="updatedate" class="control-label"><?php echo $PackageName; ?></label>
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Lưu Trữ Tối Đa:</label>
						<div class="col-sm-8">
							<label name="updatedate" class="control-label"><?php echo $MaxGB." GB"; ?></label>
						</div>
					</div>
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Số User Tối Đa:</label>
						<div class="col-sm-8">
							<label name="updatedate" class="control-label"><?php echo $MaxUser." User"; ?></label>
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="name" class="col-sm-4 control-label">Tên Công Ty</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="client_name" name="client_name" placeholder="Tên Công Ty" value="<?php echo htmlspecialchars($ClientName); ?>">
							
							
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-4 control-label">Email</label>
						<div class="col-sm-8">
							<input type="email" class="form-control" id="client_email" name="client_email" placeholder="example@domain.com" value="<?php echo htmlspecialchars($ContactEmail); ?>">
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="phone" class="col-sm-4 control-label">Phone</label>
						<div class="col-sm-8">
							<input type="phone" class="form-control" id="client_phone" name="client_phone" placeholder="" value="<?php echo htmlspecialchars($ContactPhone); ?>">
							
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="phone" class="col-sm-4 control-label">Password Cũ</label>
						<div class="col-sm-8">
							<input type = "password"  class="form-control" id="OldPassword" name="OldPassword" placeholder="" >
							
							
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
						<div class="col-sm-12 col-sm-offset-5">
							<input id="submit" name="submit" type="submit" value="Thay Đổi" class="btn btn-primary">
						</div>
					</div>
					
				</form> 
			</div>
		</div>
	

	
		
		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h2 class="page-header text-center">Lịch Sử Giao Dịch</h2>
				
				<table class="col-md-6 col-md-offset-0" border="1" style="width:100%;">
					<tr>
					<th>Ngày</th>
					<th>Nội Dung Giao Dịch</th>
					<th  style="text-align:right">Số Tiền (VND)</th>
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
						
						
						
						
						echo "<tr>
								<td>".date('d/m/Y',  $PaymentDate )."</td>
								<td>$PayDescription</td>
								<td style='text-align:right'>".number_format($TotalPay,0,',','.')."</td>
							</tr>";
						
					} ?>
					
				</table>

			</div>
		</div>

		

 


<?php
// do php stuff
include('template/footer.php');
?>

<?php
// do php stuff
include('template/form-upgrade-package.php');
?>

<?php
// do php stuff
include('template/footer-common.php');
?>

 
