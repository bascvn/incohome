<?php
$servername = "localhost";
$username = "incoclient";
$password = "~vbfgrt45@";
$dbname = "incoclient";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$search = $_GET["ClientCode"];
$sql = "SELECT * FROM Client WHERE ClientCode = '".$search."'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	$row['msg'] ='';
    	$row['url_ios'] ='';
    	$row['url_android'] ='';
    	$row['android_ver'] ='2.0';
    	$row['ios_ver'] ='';
    //	$row['BuildNumber'] ='12';
		$conn->close();
		echo '{"status":"ok","data":'.json_encode($row).'}';   
		exit();
	}
} else {
    
}
$conn->close();
echo '{"status":"error","data":'.json_encode($row).'}';  
?>