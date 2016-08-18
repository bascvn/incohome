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
		
?>



<!--  transaction dialog -->
<div class="modal fade" id="upgradePackageModal" tabindex="-1" role="dialog" aria-labelledby="upgradePackageModal" aria-hidden="true">
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
							<option value="0">--</option>

<?php
							for($j=0;$j<sizeof($package_data);$j++){
								$package = $package_data[$j];
								if($package['PackageID'] >$PackageID && $package['AdditionalType'] == 0){
									echo "<option value='". $package['PackageID']  ."'   data-price='". $package['PackagePrice']  ."'  data-name='". $package['PackageName']  ."' >". $package['PackageName']."</option>";	
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
								value="<?php echo $DateExpired; ?>">
								
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
	
	$("#bt_save_upgrade").click(function() {
		
		
		var up_package =  $('#sel_up_package').val();
		var add_gb =  $("#txt_add_gb").val();
		var cb_host_own_server = $("#cb_host_own_server").prop('checked');
		
		if(up_package==0 && add_gb==0 && !cb_host_own_server){
			 	
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
		
		var ClientID = '<?php echo $ClientID;?>';
		var cb_add_transaction = $("#cb_add_transaction").prop('checked'); 
		
		
		

		var post_uri  = "<?php echo cm_get_full_api_url("www", "client.upgrade");?>"; 
		//var post_uri  = "http://localhost/inco/gateway.php?controller=client.upgrade";
		
		$("#md_waiting").modal({backdrop: 'static', keyboard: false});
		$.post(post_uri,
			{
				ClientID: ClientID,
				up_package: up_package,
				add_gb: add_gb,
				host_own_server: cb_host_own_server,
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
