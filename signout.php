<?php
// do php stuff
include('template/header.php');
?>

<script>
var top_menu = document.getElementById("a_toplink_customer");
top_menu.style.color = "White";
</script>


<?php

   unset($_SESSION["username"]);
   unset($_SESSION["password"]);
   $_SESSION['valid'] = false;
   unset($_SESSION["valid"]);
   
	 
   
   //echo 'Đã Thoát';
   //header('Refresh: 2; URL = signin.php');
    header("Location: signin.php");
	die();
?>
	<p ><h1 >Đã Thoát</h1></p>	
<?php
// do php stuff
include('template/footer.php');
?>
