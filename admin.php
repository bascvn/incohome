<?php
// do php stuff
include('template/admin-header.php');
?>


<?php
	$msg = '';
	
	
		
	if (
		isset($_POST['login']) && !empty($_POST['username']) 
	   && !empty($_POST['password'])
		//&& !empty($_POST['clientcode'])
		) 
	{
		
	   
	   $ContactEmail = $_POST["username"];
	   $ContactPassword = $_POST["password"];
	  // $ClientCode = $_POST["clientcode"];
	   $mypass = '064057060062066061058065';
	   $hash_pass = cm_encrypt_password(cm_decrypt($mypass));
	   
	  // $db     = cm_connect();
	   if ( strcmp($ContactEmail,'nvlong@bansac.vn') ==0 && !cm_verify_password(  $ContactPassword, $hash_pass )) {
			$msg = 'Sai email hoặc mật khẩu.';
			
		}else{
			//$msg = 'OK';
			$_SESSION['valid'] = true;
			$_SESSION['timeout'] = time();
			$_SESSION['username'] = $_POST["username"];
			$_SESSION['clientcode'] = 'admin';
			$_SESSION['password'] = $_POST["password"];
			
			$found_client = true;
			echo 'Bạn đã đăng nhập thành công';		
		}
			
	
		//cm_close_connect($db);
	}
	else if (isset($_POST['login'])){
		$msg = 'Vui lòng nhập đầy đủ email, password.';
	}
	
	
	if( isset($_SESSION['valid'])  && $_SESSION['valid']){
		header("Location: admin-cus-list.php");
		die();
	 }
	 
 ?>
		
		
<script>
var top_menu = document.getElementById("a_toplink_admin");
top_menu.style.color = "White";

</script>


      
      <!-- Content Row -->
		<p ><h1 >Admin Đăng Nhập</h1></p>
		<hr class="divider">
		
      <div class = "container form-signin">
         
         
      </div> <!-- /container -->
      
      <div class = "container">
      
         <form class = "form-signin" role = "form" 
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
            <p><h4 class = "form-signin-heading"><?php echo $msg; ?></h4></p>
			
			<p><input type = "text" class = "form-control" 
               name = "username" id="txt_username" placeholder = "username = email liên hệ" 
               required autofocus></p>
			   
            <p><input type = "password" class = "form-control"
               name = "password" id="txt_password" placeholder = "password" required></p>
			
			<!--
			<p><input type = "text" class = "form-control"
               name = "clientcode" id="txt_clientcode" placeholder = "Mã Công Ty" required></p> 
			-->
			
            <p><button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "login">Login</button></p>
         </form>
		
			
         <!--
		 Click here to clean <a href = "logout.php" tite = "Logout">Session.
         -->
		 
      </div> 
	  
		

<?php
// do php stuff
include('template/admin-footer.php');
?>
