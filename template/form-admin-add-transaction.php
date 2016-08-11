<?php

$tz_name = "Asia/Ho_Chi_Minh";
$tz = new DateTimeZone($tz_name);
   
$dt = new DateTime();
$dt->setTimezone($tz);
$TrantractionDate =  $dt->format('d/m/Y');
		
?>


<!--  transaction dialog -->
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModal" aria-hidden="true">
    <div class="modal-dialog my-modal">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Thêm Giao Dịch</h4>
            </div>
            <div class="modal-body my-modal-body">
                
				<form class="form-horizontal" role="form" method="post">	
					
					
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Ngày</label>
						<div class="col-sm-7">
							<input  class="form-control DatePicker" type="text" data-role="date" id="TrantractionDate" name="TrantractionDate" 
							readonly="readonly" data-inline="true" style="background-color : #ffffff;"
								value="<?php echo $TrantractionDate; ?>">
								
						</div>
					</div>
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Chọn Gói</label>
						<div class="col-sm-7">
					
							<select class="form-control" name="NewClientPackageID" id="NewClientPackageID">
							<option value="0">--</option>

<?php
							for($j=0;$j<sizeof($package_data);$j++){
								$package = $package_data[$j];
								echo "<option value='". $package['PackageID']  ."'   data-price='". $package['PackagePrice']  ."'  data-name='". $package['PackageName']  ."' >". $package['PackageName']."</option>";	
								
							}				
?>
							</select>
						</div>
					</div>		
					
					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Số Tiền(VND)</label>
						<div class="col-sm-7">
							<input  class="form-control" id="TrantractionSubtotal" name="TrantractionSubtotal"  value="0">
						</div>
					</div>
					
					
								

					<div class="form-group">
						<label for="name" class="col-sm-5 control-label">Nội Dung Giao Dịch</label>
						<div class="col-sm-7">
								<textarea class="form-control" rows="4" name="TrantractionDescription" id="TrantractionDescription"></textarea>
						</div>
					</div>
				</form>		
					
				
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" id="bt_add_transaction"  class="btn btn-primary">Thêm</button>
        </div>
    </div>
  </div>
</div>



<!--  package upgrade dialog -->
<Script>
	
	
	$('#NewClientPackageID').on('change', function() {
		var selected = $(this).find('option:selected');
		var pName = selected.data('name');
		$('#TrantractionDescription').val("Buy package "+pName);
		
		
		var price = selected.data('price'); 
		$('#TrantractionSubtotal').val(numberWithCommas(price));
		
	   
		
	});

	
	$("#bt_add_transaction").click(function() {
		
		/*
		var dateArr = $("#TrantractionDate").val().split("/"); // dd/mm/yyyy
		var d  = new Date(dateArr[2], dateArr[1] - 1, dateArr[0]);
		var ms = d.valueOf();
		var TrantractionDate = ms / 1000;
		*/
		var TrantractionDate = convertDateStringToSenconds($("#TrantractionDate").val());
		var NewClientPackageID =  $("#NewClientPackageID").val();
		var TrantractionSubtotal =  $("#TrantractionSubtotal").val();
		var TrantractionSubtotal = TrantractionSubtotal.replace(/\./g , "");
		
		var TrantractionDescription = $('#TrantractionDescription').val();
		if(TrantractionDescription.length ==0 ){
			 	
			$("#md_warning_body_text").text("Vui lòng mô tả giao dịch.");
			$("#md_warning_body_text").removeClass("alert-success");
			$("#md_warning_body_text").addClass("alert-warning");
			$("#md_warning").modal({backdrop: 'static', keyboard: false});
			return;
		}
		
		var ClientID = '<?php echo $ClientID;?>';
		
		
		//var post_uri  = "http://localhost/inco/gateway.php?controller=client.send_upgrade_request"; 
		//var post_uri  = "<?php echo cm_get_full_api_url("www", "client.add_transaction");?>"; 
		var post_uri  = "http://localhost/inco/gateway.php?controller=client.add_transaction";
		
		$("#md_waiting").modal({backdrop: 'static', keyboard: false});
		$.post(post_uri,
			{
				ClientID: ClientID,
				TrantractionDate: TrantractionDate,
				TrantractionSubtotal: TrantractionSubtotal,
				TrantractionDescription: TrantractionDescription,
				ClientPackageID:NewClientPackageID,
				
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
		
		
		$("#transactionModal").modal("toggle");
			
	});
	
	
	$('#transactionModal').on('hidden', function () {
		// do something…
		$("#TrantractionSubtotal").val("");
		$('#TrantractionDescription').val(''); 
		
	});


	$('#transactionModal').on('hidden.bs.modal', function () {
	  // do something…
	  $("#TrantractionSubtotal").val("");
		$('#TrantractionDescription').val(''); 
	});
	

</Script>
