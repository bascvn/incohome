<?php
// do php stuff
include('template/header.html');
?>

		<!-- Page Content -->
        <!-- Heading Row -->
        <div class="row">
            <div class="col-md-8">
                <img class="img-responsive img-rounded" src="img/main_banner.jpg" alt="">
            </div>
            <!-- /.col-md-8 -->
            <div class="col-md-4">
                <h1>INCO</h1>
                <p>Là hệ thống quản trị dự án, quản trị hoạt động nội bộ thường nhật của các công ty, được cho thuê và đặt tại website kiemtraduan.net. </p>
				
				<p>Đã là khách hàng hãy truy cập thông qua <b>subdomain.kiemtraduan.net</b></p>
        		<p><input class="input input-primary " type="text" name="subdomain" placeholder="subdomain" id="txt_access_subdomain">
                <a class="btn btn-primary  "   onclick="goto_subdomain();" target="_blank">Truy Cập</a></p>
            </div>
            <!-- /.col-md-4 -->
        </div>
        <!-- /.row -->

        <hr>

        <!-- Call to Action Well -->
        <div class="row">
            <div class="col-lg-12">
                <div class="well text-center text-discount">
                    Đợt khuyến mãi khủng 20% từ ngày 1/7/2016 đến ngày 30/8/2016, hãy liên hệ ngay với chúng tôi&nbsp;&nbsp;
                    <a class="btn btn-danger  " href="contact.php?message=Tôi quan tâm giảm giá">Liên Hệ</a> 
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
		
		 <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <p ><h1 class="h1_head">Chức Năng</h1></p>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="img/feature_pm.png" alt="">
                    <div class="caption">
                        <h4>Quản Trị</h4>	
						<ul>
						<li class="li_list" >Nội dung dự án</li>
						<li class="li_list">User, phòng ban</li>
						<li class="li_list">Công việc thành viên</li>
						<li class="li_list">Thời gian, trạng thái dự án</li>
                        </ul>	
						
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="img/feature_permission.png" alt="">
                    <div class="caption">
                         <h4>Phân Quyền</h4>
						<ul>
						<li class="li_list" >Dự án</li>
						<li class="li_list">Nhóm User</li>
						<li class="li_list">Đối tượng thông tin</li>
						<li class="li_list"></li>
                        </ul>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <img src="img/feature_alert.png" alt="">
                   <div class="caption">
                         <h4>Thông Báo Hoạt Động</h4>
						 
						<ul>
						<li class="li_list" >Bằng Email</li>
						<li class="li_list" >Bằng tin nhắn (cho App)</li>
						<li class="li_list" ></li>
						<li class="li_list" ></li>
						
                        </ul>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                     <img src="img/feature_dashboard.png" alt="">
                    <div class="caption">
                         <h4>Dashboard</h4>
						<ul>
						<li class="li_list" >dự án</li>
						<li class="li_list">Công việc</li>
						<li class="li_list">Trạng thái</li>
						<li class="li_list">User</li>
                        </ul>
                        </p>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->
		

        <!-- Content Row -->
		<p ><h1 class="h1_head">Thông Tin Thêm</h1></p>
		<hr class="divider">
        <div class="row">
            <div class="col-md-4">
                <h2>Chi Tiết Chức Năng</h2>
                <p class="p_list">Quản trị nhiều dự án trong một công ty bao gồm quản trị nội dung, tiến trình, trạng thái, bình luận trên các đối tượng: công việc (task), vấn đề nóng (ticket), thảo luận (discussion).</p>
                <p class="p_list">Phân quyền theo nhóm user hoặc user riêng lẻ trên các đối tượng: dự án, công việc, vấn đề nóng, thảo luận</p>
                <p class="p_list">Thông báo nhanh chóng bằng email đến các cá nhân liên quan khi có bất kỳ sự thay đổi nào trên dự án</p>
                <!--
                <a class="btn btn-default" href="#">More Info</a>
                -->
                
            </div>
            <!-- /.col-md-4 -->
            <div class="col-md-4">
                <h2>Tích Hợp Di Động</h2>
                <p class="p_list">Tích hợp ứng dụng cho iPhone và Android để xem nội dụng trên các dự án, nhận tin nhắn khi có thay đổi, hoặc thêm bình luận cho các đối tượng: công việc, chủ đề nóng, thảo luận.</p>
                <!--
                <a class="btn btn-default" href="#">More Info</a>
                -->
                <p class="p_list">Download Android App: <a href="https://play.google.com/store/apps/details?id=vn.bansac.inco">INCO PM</a> </p>
                <p class="p_list">Download iOS App: đang xây dựng (soon)</p>
                
            </div>
            <!-- /.col-md-4 -->
            <div class="col-md-4">
                <h2>Lưu Trữ Đám Mây</h2>
                <p class="p_list">Với xu thế điện toán đám mây, bạn không phải lo việc xây dựng hệ thống, dự phòng dữ liệu, bảo mật. Chúng tôi sẽ thực hiện việc đó giúp bạn.</p>
                <p class="p_list">Truy cập mọi lúc mọi nơi, 24/7 bằng tất cả các thiếi bị chứa trình duyệt web kết nối internet</p>
                <!--
                <a class="btn btn-default" href="#">More Info</a>
                -->
                
            </div>
            <!-- /.col-md-4 -->
        </div>
        <!-- /.row -->

<?php
// do php stuff
include('template/footer.html');
?>

<script>

var top_menu = document.getElementById("a_toplink_overview");
top_menu.style.color = "White";




function goto_subdomain() {
	
	var txt = document.getElementById("txt_access_subdomain").value;
    //alert(txt);
	window.open('http://'+txt+'.kiemtraduan.net');
	
}

$('#txt_access_subdomain').keyup(function(e){
    if(e.keyCode == 13)
    {
        //$(this).trigger("enterKey");
		goto_subdomain();
    }
});

</script>
