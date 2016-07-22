<?php
//==============================================================================================================
// this header's code must be embeded in every AJAX controler file
//==============================================================================================================

require_once 'common.php';
require_once('PHPMailer/PHPMailerAutoload.php');
$url         = explode('.', $_GET['controller']);
$task        = $url[1];
$varFunction = "$task";
$varFunction();
//==============================================================================================================
?>


<?php
//======================================================================================
function get_used_memory()
{	
	$ClientCode = $_POST["ClientCode"];
	echo cm_get_dir_size(ROOT_SERVER_PATH.$ClientCode);
}

?>