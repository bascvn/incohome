<!--  transaction dialog -->
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModal" aria-hidden="true">
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
			
			$("#transactionModal").modal("toggle");
			
	});
	
	
	$('#transactionModal').on('hidden', function () {
		// do something…
		$("#cb_host_own_server").prop('checked', false);
		$("#txt_add_gb").removeAttr("disabled"); 
		$("#sel_up_package").removeAttr("disabled"); 
		$("#txt_add_gb").val("0");
		$('#sel_up_package').val('0'); 
		$('#txt_request_other').val(''); 
	});


	$('#transactionModal').on('hidden.bs.modal', function () {
	  // do something…
	  	$("#cb_host_own_server").prop('checked', false);
		$("#txt_add_gb").removeAttr("disabled"); 
		$("#sel_up_package").removeAttr("disabled"); 
		$("#txt_add_gb").val("0");
		$('#sel_up_package').val('0'); 
		$('#txt_request_other').val(''); 
	})
	

</Script>
