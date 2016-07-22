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
   $PackageName = '';
   $ClientID = 0;
  
   
   
   
   $db     = cm_connect();
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
	}
	
	if(!$found_client){
		$msg = 'Công ty không đã hết hạn.';
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
		
		//$DateCreatedObj = new DateTime("@$DateCreated");	
	}
		
	
	
	
	$DateCurrent=time();
	$DateUsed = floor(($DateCurrent - $DateUpdated) / (24*60*60));
	$DateTotal = ceil(($DateExpired - $DateUpdated) / (24*60*60));
	
	
	//get used memory
	$params_arr = array(
			'ClientCode' => $ClientCode
	);
	
	
	$uri_get_used_memory = cm_get_full_api_url($ClientCode, "client.get_used_memory"); 
	$UsedGB = cm_http_post($uri_get_used_memory,$params_arr);	
	
	//echo $uri_get_used_memory;
	
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
	
	
	cm_close_connect($db);
	
?>


<script>
var top_menu = document.getElementById("a_toplink_customer");
top_menu.style.color = "White";
</script>

		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h1 class="page-header text-center">Tài Khoản <?php echo "<p>$ClientName</p>";?></h1>
				
				<form class="form-horizontal" role="form" method="post" action="contact.php">
					
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
							<input type="text" class="form-control" id="name" name="name" placeholder="Tên Công Ty" value="<?php echo htmlspecialchars($ClientName); ?>">
							
							
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-sm-4 control-label">Email</label>
						<div class="col-sm-8">
							<input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value="<?php echo htmlspecialchars($ContactEmail); ?>">
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="phone" class="col-sm-4 control-label">Phone</label>
						<div class="col-sm-8">
							<input type="phone" class="form-control" id="phone" name="phone" placeholder="" value="<?php echo htmlspecialchars($ContactPhone); ?>">
							
							
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
  				<h1 class="page-header text-center">Thống Kê Sử Dựng</h1>
				
				<form class="form-horizontal" role="form" method="post" action="contact.php">
					
					<div class="form-group">
						<label for="createdate" class="col-sm-4 control-label">Số User Đã Tạo:</label>
						<div class="col-sm-8">
							<label name="createdate" class="control-label"><?php echo $MaxUser ." / ".$MaxUser ; ?></label>
						</div>
					</div>
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Dung Lượng Đã Dùng:</label>
						<div class="col-sm-8">
							<label name="updatedate" class="control-label"><?php echo $UsedGB." / ".$MaxGB." GB" ; ?> </label>
						</div>
					</div>
					
					<div class="form-group">
						<label for="updatedate" class="col-sm-4 control-label">Số Ngày Sử Dụng:</label>
						<div class="col-sm-8">
							<label name="updatedate" class="control-label"><?php echo $DateUsed." / ".$DateTotal ; ?> </label>
						</div>
					</div>
					
					
					
					<div class="form-group">
						<div class="col-sm-12 col-sm-offset-5">
							<input id="submit" name="submit" type="submit" value="Nâng Cấp" class="btn btn-primary">
						</div>
					</div>
					
				</form> 
			</div>
		</div>
		
		<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<h1 class="page-header text-center">Lịch Sử Giao Dịch</h1>
				
				<table class="col-md-6 col-md-offset-0" border="1" style="width:100%;">
					<tr>
					<th>Ngày</th>
					<th>Nội Dung Giao Dịch</th>
					</tr>
					
					<?php for($i=0;$i<sizeof($payment_data);$i++){
						$row = $payment_data[$i];
						$PaymentDate = 	$row['DateTime'];
						$PayDescription = $row['Description'];
						
						echo "<tr>
								<td>".date('d/m/Y',  $PaymentDate )."</td>
								<td>$PayDescription</td>
							</tr>";
						
					} ?>
					
				</table>

			</div>
		</div>
		

<?php
// do php stuff
include('template/footer.php');
?>
