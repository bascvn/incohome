<?php
// do php stuff
include('template/header.php');
?>


<?php
	$msg = '';
		
	if (isset($_POST['login']) && !empty($_POST['username']) 
	   && !empty($_POST['password'])
		&& !empty($_POST['clientcode'])) 
	{
		
	   
	   $ContactEmail = $_POST["username"];
	   $ContactPassword = $_POST["password"];
	   $ClientCode = $_POST["clientcode"];
	   
	   $db     = cm_connect();
	   $query  = "SELECT `Client`.*
				FROM `Client`
 				WHERE LOWER(`Client`.`ContactEmail`) = LOWER('$ContactEmail') AND `Client`.ClientCode = '$ClientCode'  AND `Client`.`RemovalFlag` = 0  ";
		
		$result = mysqli_query($db, $query);
		//$data   = array();        
		$found_client = false;
		while ($row = mysqli_fetch_array($result)) {
			
			if (!password_verify(  $ContactPassword, $row["ContactPassword"])) {
				break;
			}
			
			$_SESSION['valid'] = true;
			$_SESSION['timeout'] = time();
			$_SESSION['username'] = $_POST["username"];
			$_SESSION['clientcode'] = $_POST["clientcode"];
			$found_client = true;
			echo 'Bạn đã đăng nhập thành công';
		}
		
		if(!$found_client){
			$msg = 'Sai email hoặc mật khẩu hoặc mã công ty.';
		}
		
		cm_close_connect($db);
	}
	else if (isset($_POST['login'])){
		$msg = 'Vui lòng nhập đầy đủ email, password và mã công ty.';
	}
	
	
	if( isset($_SESSION['valid'])  && $_SESSION['valid']){
		  header("Location: customer.php");
			die();
	 }
	 
 ?>
		
		
<script>
var top_menu = document.getElementById("a_toplink_customer");
top_menu.style.color = "White";

/*
function validateEmail(sEmail) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
}​
*/

function validateEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}


function forgot_password() {

	
	var txt_username = document.getElementById("txt_username").value;
	var txt_clientcode = document.getElementById("txt_clientcode").value;
	
	
	if(txt_username.length<=0 || txt_username.length<=0){
		alert("Vui lòng nhập đầy đủ Username và mã công ty");
	}
	else if(!validateEmail(txt_username)){
		alert("Vui lòng nhập đúng username là email.");
	}
	else{
		window.location = "lostpass.php?username="+txt_username+ "&clientcode=" + txt_clientcode;
	}
	
	
	//alert(txt_clientcode);
}



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
               name = "username" id="txt_username" placeholder = "username = email liên hệ" 
               required autofocus></p>
			   
            <p><input type = "password" class = "form-control"
               name = "password" id="txt_password" placeholder = "password" required></p>
			
			
			<p><input type = "text" class = "form-control"
               name = "clientcode" id="txt_clientcode" placeholder = "Mã Công Ty" required></p> 
			   
            <p><button class = "btn btn-lg btn-primary btn-block" type = "submit" 
               name = "login">Login</button></p>
         </form>
		 <a href = "#"  onclick="forgot_password();" tite = "Forgot Password">Quên Password?</a> (vui lòng nhập username (là email) và mã công ty)
			
         <!--
		 Click here to clean <a href = "logout.php" tite = "Logout">Session.
         -->
		 
      </div> 
	  
		

<?php
// do php stuff
include('template/footer.php');
?>
