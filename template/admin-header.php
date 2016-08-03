<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>INCO - kiemtraduan.net</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/small-business.css" rel="stylesheet">
	
	<!-- Custom CSS -->
    <link href="css/flexslider.css" rel="stylesheet">
	

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->



	
<?php
require_once 'common.php';

ob_start();
session_start();
   
$parts=explode('.', $_SERVER["SERVER_NAME"]);
//var_dump($parts);
if(sizeof($parts)>0){
	$subdomain = strtolower($parts[0]);
	//echo $subdomain;
	
	if(strcmp($subdomain,"www")!=0 
	   && strcmp($subdomain,"kiemtraduan")!=0 
	   && strcmp($subdomain,"localhost")!=0 
	   )
	{
	   		$url = sprintf('%s://%s%s/%s/',
				$_SERVER['SERVER_PORT'] == 80 ? 'http' : 'https',
				$_SERVER['SERVER_NAME'], 
				rtrim(dirname($_SERVER['PHP_SELF']), '/'),
				$subdomain);
				
			header("Location: $url");
			exit;

	}
	
	
	
}
?>

</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">
                    <img src="img/inco_banner_150x50.png" alt="">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                  
					<li>
                        <a   id="a_toplink_admin"  href="admin.php">Admin</a>
                    </li>
<?php

	if( isset($_SESSION['admin-valid'])  && $_SESSION['admin-valid']){
		
			echo 	
					'<li>
                        <a   id="a_toplink_admin_cus_list"  href="admin-cus-list.php">Customer List</a>
                    </li>
					<li>
                        <a   id="a_toplink_admin_email"  href="admin-email.php">Email List</a>
                    </li>
					<li>
                        <a   id="a_toplink_admin_user"  href="admin-user.php">User List</a>
                    </li>
					<li>
                        <a   id="a_toplink_signout"  href="admin-signout.php">Thoát</a>
                    </li>';
	}
?>
					
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
	
	<div class="container">