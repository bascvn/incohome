<?php
// do php stuff
include('template/header.php');
?>

<?php

   $db     = cm_connect();
	
	$PackageLite;
	$PackageBasic;
	$PackageAdvanced;
	$PackagePremium;
	$PackageAddGB;
	$PackageHostOwnServer;
	
	
	//get package 
	$query  = "SELECT `Package`.*
			FROM `Package`
			WHERE   `Package`.RemovalFlag = 0";
	
	//echo $query;
	$result = mysqli_query($db, $query);
	$package_data   = array();        
	while ($row = mysqli_fetch_array($result)) {
		array_push($package_data,$row);
		
		if($row['PackageID'] == 1){
			$PackageLite = $row;
		}
		else if($row['PackageID'] == 2){
			$PackageBasic = $row;
		}
		else if($row['PackageID'] == 3){
			$PackageAdvanced = $row;
		}
		else if($row['PackageID'] == 4){
			$PackagePremium = $row;
		}
		else if($row['PackageID'] == 5){
			$PackageAddGB = $row;
		}
		
		else if($row['PackageID'] == 6){
			$PackageHostOwnServer = $row;
		}
	}
	
	
	cm_close_connect($db);
?>

<script>
var top_menu = document.getElementById("a_toplink_price");
top_menu.style.color = "White";
</script>

	 <!-- Page Content -->
        <!-- Jumbotron Header -->
        <header class="jumbotron hero-spacer" >
            <h3> Chúng tôi cho thuê hệ thống theo thời gian, số lượng user và dung lượng, hãy liên hệ với chúng tôi để được dùng thử.</h3>
		
            <p class="text-discount">Đợt khuyến mãi khủng 20% từ ngày 1/7/2016 đến ngày 30/8/2016, hãy liên hệ ngay với chúng tôi&nbsp;&nbsp;
                    <a class="btn btn-danger  " href="contact.php?message=Tôi quan tâm giảm giá">Liên Hệ</a> </p>
        </header>
        <hr>

        <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Bảng Giá</h3>
				<h4 class="li_list">Chỉ áp dụng các gói thời gian: 6 tháng, 1 năm hoặc 2 năm</h4>
            </div>
        </div>
        <!-- /.row -->

        <!-- Page Features -->
        <div class="row text-center">

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <!--
					<img src="img/price_lite.png" alt="">
					-->
					<div class="package_block"> 
						<p class="package_title"><?php echo strtoupper($PackageLite['PackageName']); ?></p>
						<p class="package_price">  <?php echo  number_format( $PackageLite['PackagePrice'],0,',','.'); ?>  VND/Tháng</p>
					</div>
                    <div class="caption">
                        <h3>Gói <?php echo $PackageLite['PackageName']; ?></h3>
                        <p>Dành cho công ty nhỏ, với: </p>
						<ul>
							<li class="li_list" ><?php echo   $PackageLite['MaxUser'] ; ?> Users</li>
							<li class="li_list"><?php echo   $PackageLite['MaxGB'] ; ?> GB Dữ Liệu</li>
							<li class="li_list">Không giới hạn dự án</li>
							<li class="li_list"><?php echo  number_format( $PackageLite['PackagePrice'],0,',','.'); ?>  VND/Tháng</li>
                        </ul>
                            <a href="contact.php?message=Tôi muốn mua gói <?php echo $PackageLite['PackageName']; ?>" class="btn btn-primary">Buy Now!</a> 
							<!--
							<a href="#" class="btn btn-default">More Info</a>
							-->
							
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <!--
					<img src="img/price_basic.png" alt="">
					-->
					<div class="package_block"> 
						<p class="package_title"><?php echo strtoupper($PackageBasic['PackageName']); ?></p>
						<p class="package_price">  <?php echo  number_format( $PackageBasic['PackagePrice'],0,',','.'); ?>  VND/Tháng</p>
					</div>
					
                    <div class="caption">
                          <h3>Gói <?php echo $PackageBasic['PackageName']; ?></h3>
                        <p>Dành cho công ty trung bình, với: </p>
						<ul>
							<li class="li_list" ><?php echo   $PackageBasic['MaxUser'] ; ?> Users</li>
							<li class="li_list"><?php echo   $PackageBasic['MaxGB'] ; ?> GB Dữ Liệu</li>
							<li class="li_list">Không giới hạn dự án</li>
							<li class="li_list"><?php echo  number_format( $PackageBasic['PackagePrice'],0,',','.'); ?>  VND/Tháng</li>
                        </ul>
                            <a href="contact.php?message=Tôi muốn mua gói <?php echo $PackageBasic['PackageName']; ?>" class="btn btn-primary">Buy Now!</a> 
							<!--
							<a href="#" class="btn btn-default">More Info</a>
							-->
							
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                    <!--
					<img src="img/price_advanced.png" alt="">
					-->
					<div class="package_block"> 
						<p class="package_title"><?php echo strtoupper($PackageAdvanced['PackageName']); ?></p>
						<p class="package_price">  <?php echo  number_format( $PackageAdvanced['PackagePrice'],0,',','.'); ?>  VND/Tháng</p>
					</div>
					
                   <div class="caption">
                          <h3>Gói <?php echo $PackageAdvanced['PackageName']; ?></h3>
                        <p>Dành cho công ty vừa, với: </p>
						<ul>
							<li class="li_list" ><?php echo   $PackageAdvanced['MaxUser'] ; ?> Users</li>
							<li class="li_list"><?php echo   $PackageAdvanced['MaxGB'] ; ?> GB Dữ Liệu</li>
							<li class="li_list">Không giới hạn dự án</li>
							<li class="li_list"><?php echo  number_format( $PackageAdvanced['PackagePrice'],0,',','.'); ?>  VND/Tháng</li>
						
                        </ul>
                            <a href="contact.php?message=Tôi muốn mua gói <?php echo $PackageAdvanced['PackageName']; ?>" class="btn btn-primary">Buy Now!</a> 
							<!--
							<a href="#" class="btn btn-default">More Info</a>
							-->
							
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 hero-feature">
                <div class="thumbnail">
                     <!--
					 <img src="img/price_premium.png" alt="">
					 -->
					 <div class="package_block"> 
						<p class="package_title"><?php echo strtoupper($PackagePremium['PackageName']); ?></p>
						<p class="package_price">  <?php echo  number_format( $PackagePremium['PackagePrice'],0,',','.'); ?>  VND/Tháng</p>
					</div>
					
                    <div class="caption">
                         <h3>Gói <?php echo $PackagePremium['PackageName']; ?></h3>
                        <p>Dành cho công ty lớn, với: </p>
						<ul>
							<li class="li_list" ><?php echo   $PackagePremium['MaxUser'] ; ?> Users</li>
							<li class="li_list"><?php echo   $PackagePremium['MaxGB'] ; ?> GB Dữ Liệu</li>
							<li class="li_list">Không giới hạn dự án</li>
							<li class="li_list"><?php echo  number_format( $PackagePremium['PackagePrice'],0,',','.'); ?>  VND/Tháng</li>
                        </ul>
                            <a href="contact.php?message=Tôi muốn mua gói <?php echo $PackagePremium['PackageName']; ?>" class="btn btn-primary">Buy Now!</a> 
							<!--
							<a href="#" class="btn btn-default">More Info</a>
							-->
							
                        </p>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->
		
		 <!-- Title -->
        <div class="row">
            <div class="col-lg-12">
                <h3>Mua Thêm: </h3>
            </div>
        </div>
		
		<div class="row">
            <div class="col-lg-12">
                 <h4 class="p_list">Mỗi GB dữ liệu: <?php echo  number_format( $PackageAddGB['PackagePrice'],0,',','.'); ?>  VND / Tháng</h4>
				 <h4 class="p_list">Nâng cấp gói: chỉ áp dụng cho gói nhỏ chuyển sang gói lớn hơn cùng thời gian. Thời gian sử dụng còn lại của gói cũ sẽ được tính ra tiền theo phương pháp bình quân và cấn trừ vào giá thành gói mới. Gói mới sẽ được ghi nhận lại từ đầu.</h4>
				 <h4 class="p_list">Cài đặt riêng hệ thống INCO trên Server của công ty bạn: <?php echo  number_format( $PackageHostOwnServer['PackagePrice'],0,',','.'); ?>  VND / tháng</h4>
            </div>
        </div>
		
<?php
// do php stuff
include('template/footer.php');
?>

       