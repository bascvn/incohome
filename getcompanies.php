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
$search = $_GET["search"];
$sql = "SELECT * FROM Client WHERE ClientCode LIKE '%".$search."%'";

$result = $conn->query($sql);
 $companys = array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        array_push($companys,$row);
    }
} else {
    
}
$conn->close();
echo '{"status":"ok","data":'.json_encode($companys).'}';
?>