<Script>
	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}
	
	function convertDateStringToSenconds(sDate) {
		var dateArr = sDate.split("/"); // dd/mm/yyyy
		var d  = new Date(dateArr[2], dateArr[1] - 1, dateArr[0]);
		var ms = d.valueOf();
		var seconds = ms / 1000;
		return seconds;
	}

	$( function() {
		$( ".DatePicker" ).datepicker({
			dateFormat: 'dd/mm/yy'
		});
	 } );
	  
</Script>

<div class="modal fade" id="md_warning" tabindex="-1" role="dialog" aria-labelledby="md_warning" aria-hidden="true">
    <div class="modal-dialog my-warn-modal">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Thông báo</h4>
            </div>
            <div class="modal-body my-warn-modal-body">
                <div class="alert" id="md_warning_body_text" >
				</div>
	
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
        </div>
    </div>
  </div>
</div>


<div class="modal fade" id="md_waiting" tabindex="-1" role="dialog" aria-labelledby="md_waiting" aria-hidden="true">
	<div class="modal-dialog my-warn-modal">
		<img src="img/waiting-wheel.gif" id="loading-indicator" style="height: 200px; width: 200px; overflow: hidden;" />
	</div>
</div>


