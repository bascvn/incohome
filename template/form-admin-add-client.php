<?php

$tz_name = "Asia/Ho_Chi_Minh";
$tz = new DateTimeZone($tz_name);
   
$dt = new DateTime();
$dt->setTimezone($tz);
$TrantractionDate =  $dt->format('d/m/Y');

$nextYear_seconds = time() + (366 * 24 * 60 * 60);
$nextYear_str = date("c", $nextYear_seconds);
$nextYear_datatime = new DateTime($nextYear_str);
$nextYear_datatime->setTimezone($tz);
$NextYearDate =  $nextYear_datatime->format('d/m/Y');
		
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
						<label for="name" class="col-sm-5 control-label">Tên Công Ty</label>
						<div class="col-sm-7">
							<input type="text"  class="form-control" id="ClientName" name="ClientName" placeholder="Not null" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Mã Công Ty</label>
						<div class="col-sm-7">
							<input type="text"  class="form-control" id="ClientCode" name="ClientCode" placeholder="Not null" value="">
						</div>
					</div>
					
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Admin Email</label>
						<div class="col-sm-7">
							<input type="text"  class="form-control" id="ClientCode" name="ClientCode" placeholder="Not null" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Sender Email</label>
						<div class="col-sm-7">
							<input type="text"  class="form-control" id="ClientCode" name="ClientCode" placeholder="Not null" value="">
						</div>
					</div>
					


					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Chọn gói</label>
						<div class="col-sm-7">
					
							<select class="form-control" name="sel_up_package" id="sel_up_package">
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
							<input  class="form-control DatePicker" type="text" data-role="date" id="upgradeFromDate" name="upgradeFromDate" 
							readonly="readonly" data-inline="true" style="background-color : #ffffff;"
								value="<?php echo $TrantractionDate; ?>">
								
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Đến Ngày</label>
						<div class="col-sm-7">
							<input  class="form-control DatePicker" type="text" data-role="date" id="upgradeToDate" name="upgradeToDate" 
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
                <button type="button" id="bt_save_upgrade"  class="btn btn-primary">Lưu Lại</button>
        </div>
    </div>
  </div>
</div>



<!--  package upgrade dialog -->
<Script>
	
	
	
	$("#bt_save_upgrade").click(function() {
		
		
		var up_package =  $('#sel_up_package').val();
		
		
		if(up_package==0 ){
			 	
			$("#md_warning_body_text").text("Chọn một hình thức nâng cấp.");
			$("#md_warning_body_text").removeClass("alert-success");
			$("#md_warning_body_text").addClass("alert-warning");
			
			$("#md_warning").modal({backdrop: 'static', keyboard: false});
			
			return;
		}
		
		
		var upgradeFromDate = convertDateStringToSenconds($("#upgradeFromDate").val());
		var upgradeToDate = convertDateStringToSenconds($("#upgradeToDate").val());
		var currentDate = new Date().getTime() / 1000;
		

		if(upgradeFromDate < (currentDate - 24*60*60)  || upgradeToDate < (currentDate - 24*60*60)
			 || upgradeToDate < upgradeFromDate)
		{
			$("#md_warning_body_text").text("Thời gian thiết lập không đúng.");
			$("#md_warning_body_text").removeClass("alert-success");
			$("#md_warning_body_text").addClass("alert-warning");
			
			$("#md_warning").modal({backdrop: 'static', keyboard: false});
			
			return;
		}
		
		
		var cb_add_transaction = $("#cb_add_transaction").prop('checked'); 
		
		//var post_uri  = "<?php echo cm_get_full_api_url("www", "client.add_transaction");?>"; 
		var post_uri  = "http://localhost/inco/gateway.php?controller=client.upgrade";
		
		$("#md_waiting").modal({backdrop: 'static', keyboard: false});
		$.post(post_uri,
			{
				up_package: up_package,
				upgradeFromDate: upgradeFromDate,
				upgradeToDate: upgradeToDate,
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
		
			
		$("#upgradePackageModal").modal("toggle");
			
	});
	
	
	$('#upgradePackageModal').on('hidden', function () {
		// do something…
		$("#cb_host_own_server").prop('checked', false);
		$("#txt_add_gb").removeAttr("disabled"); 
		$("#sel_up_package").removeAttr("disabled"); 
		$("#txt_add_gb").val("0");
		$('#sel_up_package').val('0'); 
		$('#txt_request_other').val(''); 
	});


	$('#upgradePackageModal').on('hidden.bs.modal', function () {
	  // do something…
	  	$("#cb_host_own_server").prop('checked', false);
		$("#txt_add_gb").removeAttr("disabled"); 
		$("#sel_up_package").removeAttr("disabled"); 
		$("#txt_add_gb").val("0");
		$('#sel_up_package').val('0'); 
		$('#txt_request_other').val(''); 
	})
	

</Script>
