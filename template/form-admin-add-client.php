<?php

$tz_name = "Asia/Ho_Chi_Minh";
$tz = new DateTimeZone($tz_name);
date_default_timezone_set($tz_name);   

/*
$dt = new DateTime();
$dt->setTimezone($tz);
$TrantractionDate =  $dt->format('d/m/Y');
*/
$CurrentSeconds = time();
$TrantractionDate = gmdate("d/m/Y", $CurrentSeconds);

$nextYear_seconds = $CurrentSeconds  + (366 * 24 * 60 * 60);

/*
$nextYear_str = date("c", $nextYear_seconds);
$nextYear_datatime = new DateTime($nextYear_str);
$nextYear_datatime->setTimezone($tz);
$NextYearDate =  $nextYear_datatime->format('d/m/Y');
*/
$NextYearDate =  gmdate("d/m/Y", $nextYear_seconds);		
?>



<!--  transaction dialog -->
<div class="modal fade" id="addNewClientModal" tabindex="-1" role="dialog" aria-labelledby="addNewClientModal" aria-hidden="true">
    <div class="modal-dialog my-modal">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Thêm Client</h4>
            </div>
            <div class="modal-body my-modal-body">
                
				<form class="form-horizontal" role="form" method="post">	
					
					
					<div class="form-group">
						
						<div class="col-sm-1" style="border: 0; box-shadow: none;">
						  <input type="checkbox"  class="form-control" style="border: 0; box-shadow: none;" value="" id="cb_add_DB" name="cb_add_DB">
						</div>
						<label for="cb_add_DB" class="col-sm-2 ">Đã tạo DB?</label>

						<div class="col-sm-1" style="border: 0; box-shadow: none;">
						  <input type="checkbox"  class="form-control" style="border: 0; box-shadow: none;" value="" id="cb_add_Email" name="cb_add_Email">
						</div>
						<label for="cb_add_DB" class="col-sm-3 ">Đã tạo Sender & Admin Email?</label>	
						
						<div class="col-sm-1" style="border: 0; box-shadow: none;">
						  <input type="checkbox"  class="form-control" style="border: 0; box-shadow: none;" value="" id="cb_deploy_server" name="cb_deploy_server">
						</div>
						<label for="cb_add_DB" class="col-sm-3 ">Đã cài đặt Server?</label>	
						
					</div>
					
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Tên Công Ty</label>
						<div class="col-sm-7">
							<input type="text"  class="form-control" id="NewClientName" name="NewClientName" placeholder="Not null" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Mã Công Ty</label>
						<div class="col-sm-7">
							<input type="text"  class="form-control" id="NewClientCode" name="NewClientCode" placeholder="Not null" value="">
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Admin Email</label>
						<div class="col-sm-7">
							
							<select class="form-control" name="AdminEmailID" id="AdminEmailID">
							<option value="0">--</option>

<?php
							for($j=0;$j<sizeof($email_data);$j++){
								$email = $email_data[$j];
								if($email['EmailType'] == 1){
									echo "<option value='". $email['EmailID']  ."'  >". $email['Email']."</option>";	
								}
								
							}				
?>
							</select>
							
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Sender Email</label>
						<div class="col-sm-7">
						
						<select class="form-control" name="NoreplyEmailID" id="NoreplyEmailID">
							<option value="0">--</option>

<?php
							for($j=0;$j<sizeof($email_data);$j++){
								$email = $email_data[$j];
								if($email['EmailType'] == 2){
									echo "<option value='". $email['EmailID']  ."'  >". $email['Email']."</option>";	
								}
								
							}				
?>
							</select>
							
						</div>
					</div>
					


					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Chọn gói</label>
						<div class="col-sm-7">
					
							<select class="form-control" name="PackageID" id="PackageID">
							<option value="0">--</option>

<?php
							for($j=0;$j<sizeof($package_data);$j++){
								$package = $package_data[$j];
								if($package['AdditionalType'] == 0){
									echo "<option value='". $package['PackageID']  ."'   data-price='". $package['PackagePrice']  ."'  data-name='". $package['PackageName']  ."' >". $package['PackageName']."</option>";	
								}
								
							}				
?>
							</select>
							
						</div>
					</div>		
					

					

					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Từ Ngày</label>
						<div class="col-sm-7">
							<input  class="form-control DatePicker" type="text" data-role="date" id="fromDate" name="fromDate" 
							readonly="readonly" data-inline="true" style="background-color : #ffffff;"
								value="<?php echo $TrantractionDate; ?>">
								
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Đến Ngày</label>
						<div class="col-sm-7">
							<input  class="form-control DatePicker" type="text" data-role="date" id="toDate" name="toDate" 
							readonly="readonly" data-inline="true" style="background-color : #ffffff;"
								value="<?php echo $NextYearDate; ?>">
								
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Tạo Giao Dịch</label>
						
						<div class="col-sm-1" style="border: 0; box-shadow: none;">
						  <input type="checkbox"  class="form-control" style="border: 0; box-shadow: none;" value="" id="cb_add_transaction" name="cb_add_transaction">
						</div>
					</div>
					
					
				</form>		
					
				
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" id="bt_add_new_client"  class="btn btn-primary">Lưu Lại</button>
        </div>
    </div>
  </div>
</div>



<!--  package upgrade dialog -->
<Script>
	
	$("#bt_add_new_client").click(function() {
		
		var PassWarning = true;
		var WarningString = '';
		
		var cb_add_DB = $("#cb_add_DB").prop('checked'); 
		var cb_add_Email = $("#cb_add_Email").prop('checked'); 
		var cb_deploy_server = $("#cb_deploy_server").prop('checked'); 
		
		
		var NewClientName =  $('#NewClientName').val().trim();
		var NewClientCode =  $('#NewClientCode').val().trim();
		var PackageID =  $('#PackageID').val();
		var AdminEmailID = $('#AdminEmailID').val();
		var NoreplyEmailID = $('#NoreplyEmailID').val();
		
		var fromDate = convertDateStringToSenconds($("#fromDate").val());
		var toDate = convertDateStringToSenconds($("#toDate").val());
		var currentDate = new Date().getTime() / 1000;
		
		if(!cb_add_DB || !cb_add_Email || !cb_deploy_server  ){
			PassWarning = false;
			WarningString  = "Cần hoàn thiện các bước khởi tạo cho client.";
		}
		
		//alert(ClientCode);
		else if(NewClientCode.length==0 || NewClientName.length==0){
			PassWarning = false;
			WarningString  = "Mã hoặc tên công ty không được rỗng.";
		}
		else if(AdminEmailID==0 ){
			
			PassWarning = false;
			WarningString  = "Vui lòng chọn admin email.";
		}
		
		else if(NoreplyEmailID==0 ){
			
			PassWarning = false;
			WarningString  = "Vui lòng chọn sender email.";
		}
		
		else if(PackageID==0 ){
			
			PassWarning = false;
			WarningString  = "Vui lòng chọn một gói.";
		}
		else if(fromDate < (currentDate - 24*60*60)  || toDate < (currentDate - 24*60*60)
			 || toDate < fromDate){
				 
			PassWarning = false;
			WarningString  = "Thời gian thiết lập không đúng.";
		}
		

		if(!PassWarning)
		{
			$("#md_warning_body_text").text(WarningString);
			$("#md_warning_body_text").removeClass("alert-success");
			$("#md_warning_body_text").addClass("alert-warning");
			$("#md_warning").modal({backdrop: 'static', keyboard: false});
			
			return;
		}
		
		var cb_add_transaction = $("#cb_add_transaction").prop('checked'); 
	
		var post_uri  = "<?php echo cm_get_full_api_url("www", "client.add_new");?>"; 
		//var post_uri  = "http://localhost/inco/gateway.php?controller=client.add_new";
		
		$("#md_waiting").modal({backdrop: 'static', keyboard: false});
		$.post(post_uri,
			{
				NewClientName: NewClientName,
				NewClientCode: NewClientCode,
				PackageID: PackageID,
				AdminEmailID: AdminEmailID,
				NoreplyEmailID: NoreplyEmailID,
				fromDate: fromDate,
				toDate: toDate,
				add_transaction: cb_add_transaction
				
			},
			
			function(data, status){
				
				$("#md_waiting").modal("toggle");
				
				
				var pos  = data.search("{"); //for remove prefix UTF8 code
				data = data.substr(pos);
				
				var obj = $.parseJSON(data);
				var statusCode = obj['status']; 
				
				if(statusCode==200){
					location.reload();
				}
				else{
					var message  = obj['message']; 
					$("#md_warning_body_text").text(message);
					//$("#md_warning_body_text").removeClass("alert-warning");
					//$("#md_warning_body_text").addClass("alert-success");
					$("#md_warning").modal({backdrop: 'static', keyboard: false});

				}
				
				//alert("Data: " + data + "\nStatus: " + status);
			}
		);
		
			
		$("#addNewClientModal").modal("toggle");
			
	});
	
	
	$('#addNewClientModal').on('hidden', function () {
		// do something…
		/*
		$("#cb_host_own_server").prop('checked', false);
		$("#sel_up_package").removeAttr("disabled"); 
		$('#sel_up_package').val('0'); 
		*/
	});


	$('#addNewClientModal').on('hidden.bs.modal', function () {
	  // do something…
	  	/*
		$("#cb_host_own_server").prop('checked', false);
		$("#sel_up_package").removeAttr("disabled"); 
		$('#sel_up_package').val('0'); 
		*/
	})
	

</Script>
