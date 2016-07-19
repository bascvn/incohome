<?php
// do php stuff
include('template/header.php');
?>

<?php

   if( !isset($_SESSION['valid'])  || !$_SESSION['valid']){
		header("Location: signin.php");
		die();
   }
?>


<script>
var top_menu = document.getElementById("a_toplink_customer");
top_menu.style.color = "White";
</script>


		<h4 class="text-success">Chúng tôi đang xây dựng trang này. Bạn vui lòng trở lại sau.</h4>
		

<?php
// do php stuff
include('template/footer.php');
?>
