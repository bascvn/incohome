<?php
// do php stuff
include('template/header.php');
?>


<?php
	$msg = '';
	
	
	
	
	if (isset($_POST['login']) && !empty($_POST['username']) 
	   && !empty($_POST['password'])) 
	{
		
	   if ($_POST['username'] == 'test' && 
		  $_POST['password'] == '1234') 
		{
		  $_SESSION['valid'] = true;
		  $_SESSION['timeout'] = time();
		  $_SESSION['username'] = 'test';
		  
		  echo 'Bạn đã đăng nhập thành công';
		  

	   }else {
		  $msg = 'Sai email hoặc mật khẩu hoặc mã công ty.';
	   }
	   
	}
	
	if( isset($_SESSION['valid'])  && $_SESSION['valid']){
		  header("Location: customer.php");
			die();
	 }
	
	
	
 ?>
		
		
<script>
var top_menu = document.getElementById("a_toplink_customer");
top_menu.style.color = "White";
</script>


      
      <!-- Content Row -->
		<p ><h1 >Đăng Nhập</h1></p>
		<hr class="divider">
		
      <div class = "container form-signin">
         
         
      </div> <!-- /container -->
      
      <div class = "container">
      
         <form class = "form-signin" role = "form" 
            action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post">
            <p><h4 class = "form-signin-heading"><?php echo $msg; ?></h4></p>
			
			<p><input type = "text" class = "form-control" 
               name = "username" placeholder = "username = email liên hệ" 
               required autofocus></p>
			   
            <p><input type = "password" class = "form-control"
               name = "password" placeholder = "password" required></p>
			
			
			<p><input type = "text" class = "form-control"
               name = "clientcode" placeholder = "Mã Công Ty" required></p> 
			   
            <p><button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "login">Login</button></p>
         </form>
			
         <!--
		 Click here to clean <a href = "logout.php" tite = "Logout">Session.
         -->
		 
      </div> 
	  
		

<?php
// do php stuff
include('template/footer.php');
?>
